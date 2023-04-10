<?php

namespace App\Core\Domain\Models\NLC\Member;

enum NlcMemberStatus: string
{
    case AWAITING_FILE_UPLOAD = 'awaiting_file_upload';
    case AWAITING_VERIFICATION = 'awaiting_verification';
    case ACTIVE = 'active';
    case NEED_REVISION = 'need_revision';
}
