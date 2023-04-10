<?php

namespace App\Core\Application\Service\GetReevaOrder;

use App\Core\Domain\Models\Reeva\ReevaTicket\ReevaTicket;
use App\Core\Domain\Repository\ReevaOrderRepositoryInterface;
use App\Core\Domain\Repository\ReevaTicketRepositoryInterface;
use App\Exceptions\SchematicsException;

class GetReevaOrderService
{
    private ReevaOrderRepositoryInterface $reeva_order_repository;
    private ReevaTicketRepositoryInterface $reeva_ticket_repository;

    public function __construct(ReevaOrderRepositoryInterface $reeva_order_repository, ReevaTicketRepositoryInterface $reeva_ticket_repository)
    {
        $this->reeva_order_repository = $reeva_order_repository;
        $this->reeva_ticket_repository = $reeva_ticket_repository;
    }
 
    /**
     * @throws SchematicsException
     */
    public function execute(GetReevaOrderRequest $request): GetReevaOrderResponse
    {
        $reeva_order = $this->reeva_order_repository->findByUserId($request->getAccount()->getUserId());
        if(!$reeva_order){
            throw new SchematicsException("Anda Tidak Memiliki Tiket Schematics Reeva", 3001, 404);
        }
        $reeva_ticket = $this->reeva_ticket_repository->getByReevaOrderId($reeva_order->getId());

        $reeva_ticket_array = array_map(function (ReevaTicket $ticket){
            //string $name, Email $email, string $no_telp, string $alamat, string $jenis_vaksinasi, string $bukti_vaksin_url, bool $is_used)
            return new ReevaTicketResponse(
                $ticket->getId()->toString(),
                $ticket->getName(),
                $ticket->getEmail(),
                $ticket->getNoTelp(),
                $ticket->getAlamat(),
                $ticket->getNik(),
                $ticket->isUsed()
            );
        }, $reeva_ticket);

        $jenis_tiket = "Presale 1";
        if ($reeva_order->getUniquePaymentCode() > 1065) $jenis_tiket = "Normal";
        else if($reeva_order->getUniquePaymentCode() > 52) $jenis_tiket = "Presale 2";

        return new GetReevaOrderResponse(
            $reeva_order->getId()->toString(),
            $reeva_order->getStatus(),
            $reeva_order->getBiaya(),
            $reeva_order->getUniquePaymentCode(),
            $jenis_tiket,
            $reeva_ticket_array
        );
    }
}
