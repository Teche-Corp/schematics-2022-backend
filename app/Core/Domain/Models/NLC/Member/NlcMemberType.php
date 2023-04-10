<?php

namespace App\Core\Domain\Models\NLC\Member;

enum NlcMemberType: string
{
    case KETUA = 'ketua';
    case ANGGOTA = 'anggota';
}
