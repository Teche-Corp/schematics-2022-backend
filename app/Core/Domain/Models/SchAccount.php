<?php

namespace App\Core\Domain\Models;

use App\Core\Domain\Models\User\UserId;
use App\Core\Domain\Models\NLC\Member\NlcMemberId;
use App\Core\Domain\Models\NPC\Member\NpcMemberId;
use App\Core\Domain\Models\NST\NstOrder\NstOrderId;
use App\Core\Domain\Models\Reeva\ReevaOrder\ReevaOrderId;

class SchAccount
{
    private UserId $user_id;
    private ?NpcMemberId $npc_member_id;
    private ?NlcMemberId $nlc_member_id;
    private ?NstOrderId $nst_order_id;
    private ?ReevaOrderId $reeva_order_id;


    /**
     * @param UserId $user_id
     * @param NpcMemberId|null $npc_member_id
     * @param NlcMemberId|null $nlc_member_id
     * @param NstOrderId|null $nst_order_id
     */
    public function __construct(UserId $user_id, ?NpcMemberId $npc_member_id, ?NlcMemberId $nlc_member_id, ?NstOrderId $nst_order_id, ?ReevaOrderId $reeva_order_id)
    {
        $this->user_id = $user_id;
        $this->npc_member_id = $npc_member_id;
        $this->nlc_member_id = $nlc_member_id;
        $this->nst_order_id = $nst_order_id;
        $this->reeva_order_id = $reeva_order_id;
    }

    /**
     * @return UserId
     */
    public function getUserId(): UserId
    {
        return $this->user_id;
    }

    /**
     * @return NpcMemberId|null
     */
    public function getNpcMemberId(): ?NpcMemberId
    {
        return $this->npc_member_id;
    }

    /**
     * @return NlcMemberId|null
     */
    public function getNlcMemberId(): ?NlcMemberId
    {
        return $this->nlc_member_id;
    }

    /**
     * @return NstOrderId|null
     */
    public function getNstOrderId(): ?NstOrderId
    {
        return $this->nst_order_id;
    }

    /**
     * @return ReevaOrderId|null
     */
    public function getReevaOrderId(): ?ReevaOrderId
    {
        return $this->reeva_order_id;
    }
}
