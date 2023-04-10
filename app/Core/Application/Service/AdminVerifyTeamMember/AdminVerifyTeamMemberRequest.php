<?php

namespace App\Core\Application\Service\AdminVerifyTeamMember;

class AdminVerifyTeamMemberRequest
{
    private string $member_id;
    private string $new_status;

    /**
     * @param string $member_id
     * @param string $new_status
     */
    public function __construct(string $member_id, string $new_status)
    {
        $this->member_id = $member_id;
        $this->new_status = $new_status;
    }

    /**
     * @return string
     */
    public function getMemberId(): string
    {
        return $this->member_id;
    }

    /**
     * @return string
     */
    public function getNewStatus(): string
    {
        return $this->new_status;
    }
}
