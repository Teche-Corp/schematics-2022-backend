<?php

namespace App\Core\Application\Service\AdminGetListTeam;

use JsonSerializable;

class AdminGetListTeamResponse implements JsonSerializable
{
    private string $team_id;
    private string $nama_team;
    private string $nama_ketua;

    /**
     * @param string $team_id
     * @param string $nama_team
     * @param string $nama_ketua
     */
    public function __construct(string $team_id, string $nama_team, string $nama_ketua)
    {
        $this->team_id = $team_id;
        $this->nama_team = $nama_team;
        $this->nama_ketua = $nama_ketua;
    }

    public function jsonSerialize(): array
    {
        return [
            'team_id' => $this->team_id,
            'nama_team' => $this->nama_team,
            'nama_ketua' => $this->nama_ketua
        ];
    }
}
