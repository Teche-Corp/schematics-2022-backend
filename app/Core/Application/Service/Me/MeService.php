<?php

namespace App\Core\Application\Service\Me;

use App\Core\Domain\Models\SchAccount;
use App\Core\Domain\Repository\NlcMemberRepositoryInterface;
use App\Core\Domain\Repository\NpcMemberRepositoryInterface;
use App\Core\Domain\Repository\NstOrderRepositoryInterface;
use App\Core\Domain\Repository\ReevaOrderRepositoryInterface;
use App\Core\Domain\Repository\SertifikatRepositoryInterface;
use App\Core\Domain\Repository\UserRepositoryInterface;
use App\Exceptions\SchematicsException;
use Exception;

class MeService
{
    private UserRepositoryInterface $user_repository;
    private NlcMemberRepositoryInterface $nlc_member_repository;
    private NpcMemberRepositoryInterface $npc_member_repository;
    private NstOrderRepositoryInterface $nst_order_repository;
    private ReevaOrderRepositoryInterface $reeva_order_repository;
    private SertifikatRepositoryInterface $sertifikat_repository;

    /**
     * @param UserRepositoryInterface $user_repository
     * @param NlcMemberRepositoryInterface $nlc_member_repository
     * @param NpcMemberRepositoryInterface $npc_member_repository
     * @param NstOrderRepositoryInterface $nst_order_repository
     * @param ReevaOrderRepositoryInterface $reeva_order_repository
     * @param SertifikatRepositoryInterface $sertifikat_repository
     */
    public function __construct(UserRepositoryInterface $user_repository, NlcMemberRepositoryInterface $nlc_member_repository, NpcMemberRepositoryInterface $npc_member_repository, NstOrderRepositoryInterface $nst_order_repository, ReevaOrderRepositoryInterface $reeva_order_repository, SertifikatRepositoryInterface $sertifikat_repository)
    {
        $this->user_repository = $user_repository;
        $this->nlc_member_repository = $nlc_member_repository;
        $this->npc_member_repository = $npc_member_repository;
        $this->nst_order_repository = $nst_order_repository;
        $this->reeva_order_repository = $reeva_order_repository;
        $this->sertifikat_repository = $sertifikat_repository;
    }

    /**
     * @throws Exception
     */
    public function execute(SchAccount $account): MeResponse
    {
        $user = $this->user_repository->find($account->getUserId());
        if (!$user) {
            SchematicsException::throw("user tidak ditemukan", 1006, 404);
        }
        $is_nlc = (bool)$this->nlc_member_repository->findByUserId($user->getId());
        $is_npc = (bool)$this->npc_member_repository->findByUserId($user->getId());
        $is_nst = (bool)$this->nst_order_repository->findByUserId($user->getId());
        $is_reeva = (bool)$this->reeva_order_repository->findByUserId($user->getId());

        $sertifikat = $this->sertifikat_repository->findByUserId($user->getId());

        return new MeResponse($user, $is_nlc, $is_npc, $is_nst, $is_reeva, $sertifikat);
    }
}
