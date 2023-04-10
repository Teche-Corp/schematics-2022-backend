<?php

namespace App\Core\Application\Service\HandleMidtransWebhook;

use App\Core\Domain\Models\NST\NstOrder\NstOrderId;
use App\Core\Domain\Models\Pembayaran\PembayaranId;
use App\Core\Domain\Models\Pembayaran\StatusPembayaran;
use App\Core\Domain\Models\Pembayaran\TipePembayaran;
use App\Core\Domain\Models\Reeva\ReevaOrder\ReevaOrderId;
use App\Core\Domain\Repository\NstOrderRepositoryInterface;
use App\Core\Domain\Repository\PembayaranRepositoryInterface;
use App\Core\Domain\Repository\ReevaOrderRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HandleMidtransWebhookService
{
    private PembayaranRepositoryInterface $pembayaran_repository;
    private ReevaOrderRepositoryInterface $reeva_order_repository;
    private NstOrderRepositoryInterface $nst_order_repository;

    /**
     * @param PembayaranRepositoryInterface $pembayaran_repository
     * @param ReevaOrderRepositoryInterface $reeva_order_repository
     * @param NstOrderRepositoryInterface $nst_order_repository
     */
    public function __construct(PembayaranRepositoryInterface $pembayaran_repository, ReevaOrderRepositoryInterface $reeva_order_repository, NstOrderRepositoryInterface $nst_order_repository)
    {
        $this->pembayaran_repository = $pembayaran_repository;
        $this->reeva_order_repository = $reeva_order_repository;
        $this->nst_order_repository = $nst_order_repository;
    }

    /**
     * @throws Exception
     */
    public function execute(HandleMidtransWebhookRequest $request)
    {
        if ($request->getTransactionStatus() === 'settlement') {
            $pembayaran = $this->pembayaran_repository->find(new PembayaranId($request->getPembayaranId()));
            if (!$pembayaran) {
                Log::warning("pembayaran untuk midtrans tidak ditemukan", [
                    'pembayaran_id' => $request->getPembayaranId(),
                    'midtrans_transaction_id' => $request->getTransactionId()
                ]);
                DB::table('midtrans_exception_log')->insert(
                    [
                        'midtrans_transaction_id' => $request->getTransactionId(),
                        'pembayaran_id' => $request->getPembayaranId(),
                        'context' => 'pembayaran tidak ditemukan'
                    ]
                );
            }
            $pembayaran->setStatusPembayaran(StatusPembayaran::VERIFIED);
            $pembayaran->setBuktiBayarUrl($request->getTransactionId());
            $this->pembayaran_repository->persist($pembayaran);

            switch ($pembayaran->getTipePembayaran()) {
                case TipePembayaran::MIDTRANS_REEVA_ORDER:
                    $reeva_order = $this->reeva_order_repository->find(new ReevaOrderId($pembayaran->getSubjectId()->toString()));
                    $reeva_order->activate();
                    $this->reeva_order_repository->persist($reeva_order);
                    break;
                case TipePembayaran::MIDTRANS_NST_ORDER:
                    $nst_order = $this->nst_order_repository->find(new NstOrderId($pembayaran->getSubjectId()->toString()));
                    $nst_order->activate();
                    $this->nst_order_repository->persist($nst_order);
                    break;
                default:
                    Log::warning("enum pembayaran bukan untuk midtrans", [
                        'pembayaran_id' => $request->getPembayaranId(),
                        'midtrans_transaction_id' => $request->getTransactionId()
                    ]);
                    DB::table('midtrans_exception_log')->insert(
                        [
                            'midtrans_transaction_id' => $request->getTransactionId(),
                            'pembayaran_id' => $request->getPembayaranId(),
                            'context' => 'enum pembayaran bukan untuk midtrans'
                        ]
                    );
            }
        }
    }
}
