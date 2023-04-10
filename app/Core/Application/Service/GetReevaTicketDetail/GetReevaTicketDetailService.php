<?php

namespace App\Core\Application\Service\GetReevaTicketDetail;

use App\Core\Domain\Models\Reeva\ReevaTicket\ReevaTicketId;
use App\Core\Domain\Repository\ReevaTicketRepositoryInterface;
use App\Exceptions\SchematicsException;

class GetReevaTicketDetailService
{
    private ReevaTicketRepositoryInterface $reeva_ticket_repository;

    public function __construct(ReevaTicketRepositoryInterface $reeva_ticket_repository)
    {
        $this->reeva_ticket_repository = $reeva_ticket_repository;
    }
 
    /**
     * @throws SchematicsException
     */
    public function execute(GetReevaTicketDetailRequest $request): ?ReevaTicketDetailResponse
    {
        $ticket_id = new ReevaTicketId($request->getTicketId());
        $ticket = $this->reeva_ticket_repository->find($ticket_id);

        if(!$ticket){
            SchematicsException::throw("Tiket tidak ditemukan", 400, 4004);
        }

        return new ReevaTicketDetailResponse(
            $ticket->getId()->toString(),
            $ticket->getName(),
            $ticket->getEmail(),
            $ticket->getNoTelp(),
            $ticket->getAlamat(),
            $ticket->getNik(),
            $ticket->isUsed() 
        );
    }
}
