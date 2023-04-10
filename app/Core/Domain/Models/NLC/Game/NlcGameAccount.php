<?php

namespace App\Core\Domain\Models\NLC\Game;

use App\Core\Domain\Models\Email;

class NlcGameAccount
{
    private string $account_id;
    private Email $email;
    private string $team_name;
    private string $region_id;
    private bool $first_login;

    public function __construct(string $account_id, Email $email, string $team_name, string $region_id, bool $first_login)
    {
        $this->account_id = $account_id;
        $this->email = $email;
        $this->team_name = $team_name;
        $this->region_id = $region_id;
        $this->first_login = $first_login;
    }

    public static function create(string $account_id, Email $email, string $team_name, string $region_id, bool $first_login): self
    {
        return new self(
            $account_id,
            $email,
            $team_name,
            $region_id,
            $first_login
        );
    }

    public function getAccountId(): string
    {
        return $this->account_id;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getTeamName(): string
    {
        return $this->team_name;
    }

    public function getRegionId(): string
    {
        return $this->region_id;
    }
    public function getFirstLogin(): bool
    {
        return $this->first_login;
    }
}
