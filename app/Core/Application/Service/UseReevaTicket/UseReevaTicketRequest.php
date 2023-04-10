<?php

namespace App\Core\Application\Service\UseReevaTicket;

class UseReevaTicketRequest {
    private string $ticketId;
    
    public function __construct(string $ticketId)
    {
        $this->ticketId = $ticketId;
    }

    public function getTicketId(): string 
    {
        return $this->ticketId;
    }
}