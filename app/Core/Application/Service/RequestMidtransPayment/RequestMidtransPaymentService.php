<?php

namespace App\Core\Application\Service\RequestMidtransPayment;

use App\Core\Domain\Models\NST\NstTicket\NstTicket;
use App\Core\Domain\Models\Pembayaran\Pembayaran;
use App\Core\Domain\Models\Pembayaran\PembayaranId;
use App\Core\Domain\Models\Pembayaran\StatusPembayaran;
use App\Core\Domain\Models\Pembayaran\SubjectId;
use App\Core\Domain\Models\Pembayaran\TipeBank;
use App\Core\Domain\Models\Pembayaran\TipePembayaran;
use App\Core\Domain\Models\Reeva\ReevaTicket\ReevaTicket;
use App\Core\Domain\Models\SchAccount;
use App\Core\Domain\Repository\NstTicketRepositoryInterface;
use App\Core\Domain\Repository\PembayaranRepositoryInterface;
use App\Core\Domain\Repository\ReevaTicketRepositoryInterface;
use App\Core\Domain\Repository\UserRepositoryInterface;
use App\Core\Domain\Service\MidtransPaymentServiceInterface;
use App\Exceptions\SchematicsException;
use Exception;
use Illuminate\Support\Facades\DB;

class RequestMidtransPaymentService
{
    private MidtransPaymentServiceInterface $midtrans_payment_service;
    private UserRepositoryInterface $user_repository;
    private PembayaranRepositoryInterface $pembayaran_repository;
    private ReevaTicketRepositoryInterface $reeva_ticket_repository;
    private NstTicketRepositoryInterface $nst_ticket_repository;

    /**
     * @param MidtransPaymentServiceInterface $midtrans_payment_service
     * @param UserRepositoryInterface $user_repository
     * @param PembayaranRepositoryInterface $pembayaran_repository
     * @param ReevaTicketRepositoryInterface $reeva_ticket_repository
     * @param NstTicketRepositoryInterface $nst_ticket_repository
     */
    public function __construct(MidtransPaymentServiceInterface $midtrans_payment_service, UserRepositoryInterface $user_repository, PembayaranRepositoryInterface $pembayaran_repository, ReevaTicketRepositoryInterface $reeva_ticket_repository, NstTicketRepositoryInterface $nst_ticket_repository)
    {
        $this->midtrans_payment_service = $midtrans_payment_service;
        $this->user_repository = $user_repository;
        $this->pembayaran_repository = $pembayaran_repository;
        $this->reeva_ticket_repository = $reeva_ticket_repository;
        $this->nst_ticket_repository = $nst_ticket_repository;
    }

    /**
     * @throws Exception
     */
    public function execute(SchAccount $account, RequestMidtransPaymentType $type): RequestMidtransPaymentResponse
    {
        $user = $this->user_repository->find($account->getUserId());
        if (!$user)
            SchematicsException::throw("user not found", 7001, 404);

        switch ($type) {
            case RequestMidtransPaymentType::REEVA_ORDER:
                $reeva_order_id = $account->getReevaOrderId();
                if (!$reeva_order_id)
                    SchematicsException::throw("account does not have reeva order", 7002, 404);

                $jumlah_tiket_terbeli = DB::select(
                    "
                        select count(1) as jumlah from reeva_order o , reeva_ticket t
                        where o.status = 'active'
                        and t.reeva_order_id = o.id
                    "
                );
                $reeva_tickets = $this->reeva_ticket_repository->getByReevaOrderId($reeva_order_id);
                if ($jumlah_tiket_terbeli[0]->jumlah + count($reeva_tickets) >= ReevaTicket::QUOTA_TICKET_PRESALE_1) {
                    SchematicsException::throw("kuota tiket sudah habis!", 1003);
                }

                if (!$pembayaran = $this->pembayaran_repository->findBySubjectIdAndTipePembayaran(
                    new SubjectId($reeva_order_id->toString()),
                    TipePembayaran::MIDTRANS_REEVA_ORDER
                )) {
                    $pembayaran = new Pembayaran(
                        PembayaranId::generate(),
                        StatusPembayaran::UNVERIFIED,
                        TipePembayaran::MIDTRANS_REEVA_ORDER,
                        new SubjectId($reeva_order_id->toString()),
                        TipeBank::MIDTRANS,
                        'midtrans', 'midtrans', '-'
                    );
                    $this->pembayaran_repository->persist($pembayaran);
                }
                break;
            case RequestMidtransPaymentType::NST_ORDER:
                $nst_order_id = $account->getNstOrderId();
                if (!$nst_order_id)
                    SchematicsException::throw("account does not have nst order", 7002, 404);

                $jumlah_tiket_terbeli = DB::select(
                        "
                    select count(1) as jumlah from nst_order o , nst_ticket t
                    where o.status = 'active'
                    and t.nst_order_id = o.id
                "
                );
                $nst_tickets = $this->nst_ticket_repository->getByNstOrderId($nst_order_id);
                if ($jumlah_tiket_terbeli[0]->jumlah + count($nst_tickets) >= NstTicket::QUOTA_TICKET) {
                    SchematicsException::throw("kuota tiket sudah habis!", 1002);
                }

                if (!$pembayaran = $this->pembayaran_repository->findBySubjectIdAndTipePembayaran(
                    new SubjectId($nst_order_id->toString()),
                    TipePembayaran::MIDTRANS_NST_ORDER
                )) {
                    $pembayaran = new Pembayaran(
                        PembayaranId::generate(),
                        StatusPembayaran::UNVERIFIED,
                        TipePembayaran::MIDTRANS_NST_ORDER,
                        new SubjectId($nst_order_id->toString()),
                        TipeBank::MIDTRANS,
                        'midtrans', 'midtrans', '-'
                    );
                    $this->pembayaran_repository->persist($pembayaran);
                }
                break;
            default:
                SchematicsException::throw("invalid enum type", 7000);
        }
        return new RequestMidtransPaymentResponse($this->midtrans_payment_service->createPaymentLink($user, $pembayaran));
    }
}
