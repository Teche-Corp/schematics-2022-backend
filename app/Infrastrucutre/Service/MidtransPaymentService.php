<?php

namespace App\Infrastrucutre\Service;

use App\Core\Domain\Models\NST\NstOrder\NstOrder;
use App\Core\Domain\Models\NST\NstOrder\NstOrderId;
use App\Core\Domain\Models\Pembayaran\Pembayaran;
use App\Core\Domain\Models\Pembayaran\TipePembayaran;
use App\Core\Domain\Models\Reeva\ReevaOrder\ReevaOrder;
use App\Core\Domain\Models\Reeva\ReevaOrder\ReevaOrderId;
use App\Core\Domain\Models\User\User;
use App\Core\Domain\Repository\NstTicketRepositoryInterface;
use App\Core\Domain\Repository\ReevaTicketRepositoryInterface;
use App\Core\Domain\Service\MidtransPaymentServiceInterface;
use App\Exceptions\SchematicsException;
use DateTime;
use DateTimeZone;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;
use function base64_encode;
use function config;
use function count;

class MidtransPaymentService implements MidtransPaymentServiceInterface
{
    private ReevaTicketRepositoryInterface $reeva_ticket_repository;
    private NstTicketRepositoryInterface $nst_ticket_repository;
    private const ADMIN_FEE_PERCENTAGE = 0.04;

    /**
     * @param ReevaTicketRepositoryInterface $reeva_ticket_repository
     * @param NstTicketRepositoryInterface $nst_ticket_repository
     */
    public function __construct(ReevaTicketRepositoryInterface $reeva_ticket_repository, NstTicketRepositoryInterface $nst_ticket_repository)
    {
        $this->reeva_ticket_repository = $reeva_ticket_repository;
        $this->nst_ticket_repository = $nst_ticket_repository;
    }

    /**
     * @throws Exception
     */
    public function createPaymentLink(User $user, Pembayaran $pembayaran): string
    {
        switch ($pembayaran->getTipePembayaran()) {
            case TipePembayaran::MIDTRANS_REEVA_ORDER:
                $tickets = $this->reeva_ticket_repository->getByReevaOrderId(new ReevaOrderId($pembayaran->getSubjectId()->toString()));
                $amount = ReevaOrder::BASE_PRICE;
                break;
            case TipePembayaran::MIDTRANS_NST_ORDER:
                $tickets = $this->nst_ticket_repository->getByNstOrderId(new NstOrderId($pembayaran->getSubjectId()->toString()));
                $amount = NstOrder::BASE_PRICE;
                break;
            default:
                SchematicsException::throw("invalid pembayaran enum", 1100);
        }
        if (0 == $jumlah_ticket = count($tickets)) SchematicsException::throw("jumlah tiket yang dipesan tidak boleh 0", 1201);
        $amount *= $jumlah_ticket;
        $payload = $this->constructPayload($user, $pembayaran, (int)$amount);

        $response = Http::withHeaders([
            "Content-Type" => "application/json",
            "Authorization" => "Basic ".base64_encode(config('app.midtrans_server_key')),
            "Accept" => "application/json"
        ])->post(config('app.midtrans_api_url')."/v1/payment-links/", $payload);

        if ($message = $response->json('error_messages')) {
            DB::table('midtrans_exception_log')->insert(
                [
                    'pembayaran_id' => $pembayaran->getId()->toString(),
                    'context' => $message[0]
                ]
            );
            Log::warning("kesalahan pada pembuatan payment link", [
                'pembayaran_id' => $pembayaran->getId()->toString(),
                'context' => $message[0]
            ]);
            if ($message[0] == "Invalid email format.") {
                SchematicsException::throw("format email user tidak valid", 1221);
            }
            SchematicsException::throw("terjadi kesalahan midtrans", 1220);
        }
        return $response->json('payment_url');
    }

    /**
     * @throws Exception
     */
    private function constructPayload(User $user, Pembayaran $pembayaran, int $amount): array
    {
        return [
            "transaction_details" => [
                "order_id" => Uuid::uuid4()->toString(),
                "gross_amount" => $amount + ceil($amount * self::ADMIN_FEE_PERCENTAGE)
            ],
            "usage_limit" => 1,
            "expiry" => [
                "start_time" => (new DateTime('now', new DateTimeZone('Asia/Jakarta')))->format("Y-m-d H:i").' +0700',
                "duration" => 30,
                "unit" => "minutes"
            ],
            "enabled_payments" => ["bni_va", "bri_va", "permata_va", "gopay"],
            "item_details" => [
                [
                    "id" => $pembayaran->getSubjectId()->toString(),
                    "name" => $pembayaran->getTipePembayaran()->value,
                    "price" => $amount,
                    "quantity" => 1,
                    "brand" => "Schematics",
                    "merchant_name" => "Schematics"
                ],
                [
                    "id" => "transactionfee-{$pembayaran->getSubjectId()->toString()}",
                    "name" => "Biaya Transaksi",
                    "price" => ceil($amount * self::ADMIN_FEE_PERCENTAGE),
                    "quantity" => 1,
                    "brand" => "Schematics",
                    "merchant_name" => "Schematics"
                ]
            ],
            "customer_details" => [
                "first_name" => $user->getName(),
                "email" => $user->getEmail()->toString(),
                "phone" => $user->getNoTelp(),
                "notes" => "Terima kasih atas pembelian tiket Schematics, silahkan ikuti instruksi untuk membayar"
            ],
            "custom_field1" => $pembayaran->getId()->toString()
        ];
    }
}
