<?php

namespace App\Core\Application\Service\AdminGetNpcTeam;

class AdminGetNpcTeamRequest
{
    private string $team_id;

    /**
     * @param string $team_id
     */
    public function __construct(string $team_id)
    {
        $this->team_id = $team_id;
    }

    /**
     * @return string
     */
    public function getTeamId(): string
    {
        return $this->team_id;
    }
}
