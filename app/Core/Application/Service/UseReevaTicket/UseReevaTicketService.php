<?php

namespace App\Core\Application\Service\UseReevaTicket;

use App\Core\Domain\Models\Reeva\ReevaTicket\ReevaTicketId;
use App\Core\Domain\Repository\ReevaTicketRepositoryInterface;
use App\Exceptions\SchematicsException;
use Illuminate\Support\Facades\DB;

class UseReevaTicketService {
    private ReevaTicketRepositoryInterface $reeva_ticket_repository;

    public function __construct(ReevaTicketRepositoryInterface $reeva_ticket_repository)
    {
        $this->reeva_ticket_repository = $reeva_ticket_repository;
    }

    public function execute(UseReevaTicketRequest $request): ?bool
    {
        $ticket_id = new ReevaTicketId($request->getTicketId());
        $ticket = $this->reeva_ticket_repository->find($ticket_id);

        if(!$ticket){
            SchematicsException::throw("Tiket tidak ditemukan", 400, 4004);
        }

        $ticket->setIsUsed(true);

        try{
            $this->reeva_ticket_repository->persist($ticket);
            return true;
        } catch (\Exception $e){
            SchematicsException::throw("Tiket tidak dapat ditukarkan", 400, 4008);
        }
    }
}