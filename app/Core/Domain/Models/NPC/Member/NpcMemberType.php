<?php

namespace App\Core\Domain\Models\NPC\Member;

enum NpcMemberType: string
{
    case KETUA = 'ketua';
    case ANGGOTA = 'anggota';
}
