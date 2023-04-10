<?php

namespace App\Core\Domain\Models\NPC\Member;

enum NpcMemberStatus: string
{
    case AWAITING_VERIFICATION = 'awaiting_verification';
    case ACTIVE = 'active';
    case NEED_REVISION = 'need_revision';
}
