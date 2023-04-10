<?php

namespace App\Core\Application\Service\GetReevaTicketDetail;

class GetReevaTicketDetailRequest
{
    private string $ticketId;

    public function __construct(string $ticketId)
    {
        $this->ticketId = $ticketId;
    }

    /**
     * @return string
     */
    public function getTicketId(): string
    {
        return $this->ticketId;
    }
}
