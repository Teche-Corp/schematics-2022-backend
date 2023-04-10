<?php

namespace App\Core\Application\Service\OrderReevaTicket;

use App\Core\Domain\Models\Email;
use App\Core\Domain\Models\Reeva\ReevaOrder\ReevaOrder;
use App\Core\Domain\Models\Reeva\ReevaOrder\ReevaOrderId;
use App\Core\Domain\Models\Reeva\ReevaOrder\ReevaOrderStatus;
use App\Core\Domain\Models\Reeva\ReevaTicket\ReevaTicket;
use App\Core\Domain\Models\Reeva\ReevaTicket\ReevaTicketId;
use App\Core\Domain\Models\SchAccount;
use App\Core\Domain\Repository\ReevaOrderRepositoryInterface;
use App\Core\Domain\Repository\ReevaTicketRepositoryInterface;
use App\Core\Domain\Repository\VoucherRepositoryInterface;
use App\Core\Domain\Service\UniquePaymentCodeInterface;
use App\Exceptions\SchematicsException;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use function count;

class OrderReevaTicketService
{
    private ReevaOrderRepositoryInterface $reeva_order_repository;
    private ReevaTicketRepositoryInterface $reeva_ticket_repository;
    private UniquePaymentCodeInterface $unique_payment_code_service;
    private VoucherRepositoryInterface $voucher_repository;

    /**
     * @param ReevaOrderRepositoryInterface $reeva_order_repository
     * @param ReevaTicketRepositoryInterface $reeva_ticket_repository
     * @param UniquePaymentCodeInterface $unique_payment_code_service
     */
    public function __construct(ReevaOrderRepositoryInterface $reeva_order_repository, ReevaTicketRepositoryInterface $reeva_ticket_repository, UniquePaymentCodeInterface $unique_payment_code_service, VoucherRepositoryInterface $voucher_repository)
    {
        $this->reeva_order_repository = $reeva_order_repository;
        $this->reeva_ticket_repository = $reeva_ticket_repository;
        $this->unique_payment_code_service = $unique_payment_code_service;
        $this->voucher_repository = $voucher_repository;
    }

    /**
     * @param OrderReevaTicketRequest $requests
     * @param SchAccount $account
     * @param string $info_sch
     * @return void
     * @throws Exception
     */
    public function execute(OrderReevaTicketRequest $request, SchAccount $account, string $info_sch): void
    {
        $currentTime = Carbon::now("Asia/Jakarta");
        $currentTime = $currentTime->toDateTimeString("minutes");
        // if (
        //     App::environment("production")
        // ) {
        //     if ($currentTime < "2022-10-07 16:00") {
        //         SchematicsException::throw("Pendaftaran Normal Tiket Schematics Reeva dibuka pada 7 Oktober 2022 16:00 WIB", 7020);
        //     }
        //     else if ($currentTime >= "2022-10-16 16:00") {
        //         SchematicsException::throw("Pendaftaran Normal Tiket Schematics Reeva telah berakhir", 7020);
        //     }
        // }

        $total_tiket_clean = $request->getJumlahTiket();
        if ($total_tiket_clean < 1) return;
        elseif ($total_tiket_clean > ReevaOrder::MAX_TICKET) {
            SchematicsException::throw("maksimal pemesanan ticket reeva melebihi batas!", 7000);
        }

        $jumlah_tiket_terbeli = DB::select(
            "
                select count(1) as jumlah from reeva_order o , reeva_ticket t
                where o.status <> 'expired'
                and t.reeva_order_id = o.id
            "
        );
        if ($jumlah_tiket_terbeli[0]->jumlah + $total_tiket_clean >= ReevaTicket::QUOTA_TICKET_NORMAL_TICKET) {
            SchematicsException::throw("kuota tiket sudah habis!", 1003);
        }

        $base_price = $currentTime < "2022-10-10 00:00" ? 149000 : 185000;

        $unique_payment_code = $this->unique_payment_code_service->getByEventType('reeva_order');
        $biaya = $total_tiket_clean * ReevaOrder::BASE_PRICE + $unique_payment_code;
        $currentTime = Carbon::now("Asia/Jakarta");
        $currentTime = $currentTime->toDateTimeString("minutes");
        if ($request->getKodeVoucher()){
            $voucher = $this->voucher_repository->findByKode($request->getKodeVoucher());
            if (!$voucher) {
                SchematicsException::throw("Voucher not found", 10002);
            }
            else {
                $biaya = $biaya - ($total_tiket_clean * $voucher->getPotongan());
                $voucher->useVoucher($total_tiket_clean);
            }
            $this->voucher_repository->persist($voucher);
        }

        if ($total_tiket_clean === 2) {
            $voucher = $this->voucher_repository->findByKode("COUPLE");
            if ($currentTime >= $voucher->getStartTime()) {
                $voucher->useVoucher(1);
                $biaya = $biaya - $voucher->getPotongan();
            }
        }
        if($total_tiket_clean === 5) {
            $voucher = $this->voucher_repository->findByKode("GROUP");
            if ($currentTime >= $voucher->getStartTime()) {
                $voucher->useVoucher(1);
                $total_tiket_clean++;
            }
        }

        if ($order_reeva = $this->reeva_order_repository->findByUserId($account->getUserId())) {
            if ($order_reeva->getStatus() != ReevaOrderStatus::EXPIRED) {
                SchematicsException::throw("user sudah mempunyai tiket!", 8000);
            }
            $order_reeva->awaitPayment();
            $order_reeva->setJumlahTiket($total_tiket_clean);
            $order_reeva->setBiaya($biaya);
            $order_reeva->setUniquePaymentCode($unique_payment_code);
            $order_reeva->setInfoSch($info_sch);
            $order_reeva->setKodeVoucher($request->getKodeVoucher());
        } else {
            $order_reeva = new ReevaOrder(
                ReevaOrderId::generate(),
                $account->getUserId(),
                ReevaOrderStatus::AWAITING_PAYMENT,
                $total_tiket_clean,
                $biaya,
                $unique_payment_code,
                $info_sch,
                $request->getKodeVoucher()
            );
        }
        $this->reeva_order_repository->persist($order_reeva);
        $reeva_tickets = $this->reeva_ticket_repository->getByReevaOrderId($order_reeva->getId());
        if (count($reeva_tickets) > 0) {
            foreach ($reeva_tickets as $reeva_ticket) {
                $reeva_ticket->setName($request->getName());
                $reeva_ticket->setEmail(new Email($request->getEmail()));
                $reeva_ticket->setNoTelp($request->getNoTelp());
                $reeva_ticket->setAlamat($request->getAlamat());
                $reeva_ticket->setNik($request->getNik());
                $this->reeva_ticket_repository->persist($reeva_ticket);
            }
        } else {
            for ($i=0; $i < $total_tiket_clean; $i++) {
                $reeva_ticket_id = ReevaTicketId::generate();

                $reeva_ticket = new ReevaTicket(
                    $reeva_ticket_id,
                    $order_reeva->getId(),
                    $request->getName(),
                    new Email($request->getEmail()),
                    $request->getNoTelp(),
                    $request->getAlamat(),
                    $request->getNik(),
                    false,
                );
                $this->reeva_ticket_repository->persist($reeva_ticket);
            }
        }
    }
}
