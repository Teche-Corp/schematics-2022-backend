<?php

namespace App\Core\Domain\Repository;

use App\Core\Domain\Models\NLC\Feedback\NlcFeedback;
use App\Core\Domain\Models\NLC\Team\NlcTeamId;

interface NlcFeedbackRepositoryInterface
{
    /**
     * @param NlcTeamId $nlc_team_id
     * @return NlcFeedback[]
     */
    public function getByTeamId(NlcTeamId $nlc_team_id): array;

    public function insert(NlcFeedback $feedback): void;
}
