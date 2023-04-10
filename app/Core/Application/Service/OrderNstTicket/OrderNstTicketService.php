<?php

namespace App\Core\Application\Service\OrderNstTicket;

use Exception;
use App\Core\Domain\Models\Email;
use Illuminate\Support\Facades\DB;
use App\Core\Domain\Models\SchAccount;
use App\Exceptions\SchematicsException;
use Illuminate\Support\Facades\Storage;
use App\Core\Domain\Models\NST\NstOrder\NstOrder;
use App\Core\Domain\Models\NST\NstOrder\NstOrderId;
use App\Core\Domain\Models\NST\NstTicket\NstTicket;
use App\Core\Domain\Models\NST\NstTicket\NstTicketId;
use App\Core\Domain\Models\NST\NstOrder\NstOrderStatus;
use App\Core\Domain\Service\UniquePaymentCodeInterface;
use App\Core\Domain\Repository\NstOrderRepositoryInterface;
use App\Core\Domain\Repository\NstTicketRepositoryInterface;
use App\Core\Application\Service\OrderNstTicket\OrderNstTicketRequest;

class OrderNstTicketService
{
    private NstOrderRepositoryInterface $nst_order_repository;
    private NstTicketRepositoryInterface $nst_ticket_repository;
    private UniquePaymentCodeInterface $unique_payment_code_service;

    /**
     * @param NstOrderRepositoryInterface $nst_order_repository
     * @param NstTicketRepositoryInterface $nst_ticket_repository
     * @param UniquePaymentCodeInterface $unique_payment_code_service
     */
    public function __construct(NstOrderRepositoryInterface $nst_order_repository, NstTicketRepositoryInterface $nst_ticket_repository, UniquePaymentCodeInterface $unique_payment_code_service)
    {
        $this->nst_order_repository = $nst_order_repository;
        $this->nst_ticket_repository = $nst_ticket_repository;
        $this->unique_payment_code_service = $unique_payment_code_service;
    }

    /**
     * @param OrderNstTicketRequest
     * @param SchAccount $account
     * @param string $info_sch
     * @return void
     * @throws Exception
     */
    public function execute(OrderNstTicketRequest $request, SchAccount $account, string $info_sch): void
    {
        $nPromo = DB::table('nst_order')->where('jumlah_tiket', 6)->count() ?? 0;
        $total_tiket_clean = $request->getJumlahTiket();
        $total_tiket = $total_tiket_clean;
        if($nPromo >= 9 && $total_tiket_clean == 6){
            $total_tiket_clean = 5;
        }
        if ($request->getJumlahTiket() < 1) return;
        if ($request->getJumlahTiket() == 6) {
            $total_tiket = 5;
        }
        elseif ($total_tiket > NstOrder::MAX_TICKET) {
            SchematicsException::throw("maksimal pemesanan ticket nst melebihi batas!", 7000);
        }
        $jumlah_ticket_terbeli = DB::select(
            "
                select count(1) as jumlah from nst_order o , nst_ticket t
                where o.status = 'active'
                and t.nst_order_id = o.id
            "
        );
        if ($jumlah_ticket_terbeli[0]->jumlah + $total_tiket >= NstTicket::QUOTA_TICKET) {
            SchematicsException::throw("kuota ticket sudah habis!", 1002);
        }

        if($this->nst_order_repository->findByUserId($account->getUserId())) {
            SchematicsException::throw('user sudah pernah memesan ticket!', 8001);
        }
        $nst_order_id = NstOrderId::generate();
        $order_nst = new NstOrder(
            $nst_order_id,
            $account->getUserId(),
            NstOrderStatus::AWAITING_PAYMENT,
            $request->getJumlahTiket(),
            $total_tiket * NstOrder::BASE_PRICE + // harga base * jumlah ticket + kode unik
            $unique_code = $this->unique_payment_code_service->getByEventType('nst_order'),
            $unique_code,
            $info_sch,
        );
        
        $this->nst_order_repository->persist($order_nst);
        if ($request->getBuktiVaksin()->getSize() > 1048576) {
            SchematicsException::throw("bukti vaksin harus dibawah 1Mb", 6001);
        }
        $vaksin_path = Storage::putFileAs("NST/Bukti", $request->getBuktiVaksin(), 'bukti_vaksin_'.$nst_order_id->toString());
        if (!$vaksin_path) {
            SchematicsException::throw("gagal menyimpan bukti vaksin untuk email: {$request->getEmail()}", 6002);
        }
        for ($i=0; $i < $total_tiket_clean; $i++) { 
            $nst_ticket_id = NstTicketId::generate();
            $nst_ticket = new NstTicket(
                $nst_ticket_id,
                $order_nst->getId(),
                $request->getName(),
                new Email($request->getEmail()),
                $request->getNoTelp(),
                $request->getAlamat(),
                $request->getTipeVaksin(),
                $vaksin_path,
                false,
            );
            $this->nst_ticket_repository->persist($nst_ticket);
        }
    }
}
