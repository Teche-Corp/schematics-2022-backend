<?php

namespace App\Core\Application\Service\PostFeedback;

use App\Core\Domain\Models\NLC\Feedback\NlcFeedback;
use App\Core\Domain\Models\NLC\Feedback\NlcFeedbackId;
use App\Core\Domain\Models\SchAccount;
use App\Core\Domain\Repository\NlcFeedbackRepositoryInterface;
use App\Core\Domain\Repository\NlcMemberRepositoryInterface;
use Exception;

class PostFeedbackService
{
    private NlcFeedbackRepositoryInterface $nlc_feedback_repository;
    private NlcMemberRepositoryInterface $nlc_member_repository;

    /**
     * @param NlcFeedbackRepositoryInterface $nlc_feedback_repository
     * @param NlcMemberRepositoryInterface $nlc_member_repository
     */
    public function __construct(NlcFeedbackRepositoryInterface $nlc_feedback_repository, NlcMemberRepositoryInterface $nlc_member_repository)
    {
        $this->nlc_feedback_repository = $nlc_feedback_repository;
        $this->nlc_member_repository = $nlc_member_repository;
    }

    /**
     * @throws Exception
     */
    public function execute(PostFeedbackRequest $request, SchAccount $account)
    {
        $nlc_member = $this->nlc_member_repository->find($account->getNlcMemberId());

        $nlc_feedback = new NlcFeedback(
            NlcFeedbackId::generate(),
            $nlc_member->getTeamId(),
            $request->getNamaSekolah(),
            $request->getTingkatKepuasan(),
            $request->getBabakSoal(),
            $request->getBabakGame(),
            $request->isTerdapatKendala(),
            $request->getKesan(),
            $request->getKritikSaran(),
            $request->getNamaKetua(),
            $request->getNamaAnggota1(),
            $request->getNamaAnggota2()
        );

        $this->nlc_feedback_repository->insert($nlc_feedback);
    }
}
