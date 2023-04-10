<?php

namespace App\Core\Application\Service\GetNstOrder;

use App\Core\Domain\Models\NST\NstTicket\NstTicket;
use App\Core\Domain\Repository\NstOrderRepositoryInterface;
use App\Core\Domain\Repository\NstTicketRepositoryInterface;
use App\Exceptions\SchematicsException;

class GetNstOrderService
{
    private NstOrderRepositoryInterface $nst_order_repository;
    private NstTicketRepositoryInterface $nst_ticket_repository;

    public function __construct(NstOrderRepositoryInterface $nst_order_repository, NstTicketRepositoryInterface $nst_ticket_repository)
    {
        $this->nst_order_repository = $nst_order_repository;
        $this->nst_ticket_repository = $nst_ticket_repository;
    }

    /**
     * @throws SchematicsException
     */
    public function execute(GetNstOrderRequest $request): GetNstOrderResponse
    {
        $nst_order = $this->nst_order_repository->findByUserId($request->getAccount()->getUserId());
        if(!$nst_order){
            throw new SchematicsException("Anda Tidak Memiliki Tiket Schematics NST", 3001, 404);
        }
        $nst_ticket = $this->nst_ticket_repository->getByNstOrderId($nst_order->getId());

        $nst_ticket_array = array_map(function (NstTicket $ticket){
            //string $name, Email $email, string $no_telp, string $alamat, string $jenis_vaksinasi, string $bukti_vaksin_url, bool $is_used)
            return new NstTicketResponse(
                $ticket->getId()->toString(),
                $ticket->getName(),
                $ticket->getEmail(),
                $ticket->getNoTelp(),
                $ticket->getAlamat(),
                $ticket->getJenisVaksinasi(),
                $ticket->getBuktiVaksinUrl(),
                $ticket->isUsed()
            );


        }, $nst_ticket);

        return new GetNstOrderResponse(
            $nst_order->getId()->toString(),
            $nst_order->getStatus(),
            $nst_order->getBiaya(),
            $nst_order->getUniquePaymentCode(),
            $nst_ticket_array
        );
    }
}
