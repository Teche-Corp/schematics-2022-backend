<?php

namespace App\Infrastrucutre\Service;

use App\Core\Domain\Models\SchAccount;
use App\Core\Domain\Models\User\User;
use App\Core\Domain\Models\User\UserId;
use App\Core\Domain\Repository\NlcMemberRepositoryInterface;
use App\Core\Domain\Repository\NpcMemberRepositoryInterface;
use App\Core\Domain\Repository\NstOrderRepositoryInterface;
use App\Core\Domain\Repository\ReevaOrderRepositoryInterface;
use App\Core\Domain\Repository\UserRepositoryInterface;
use App\Core\Domain\Service\JwtManagerInterface;
use App\Exceptions\SchematicsException;
use DateInterval;
use DateTime;
use Exception;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use UnexpectedValueException;

class JwtManager implements JwtManagerInterface
{
    public UserRepositoryInterface $user_repository;
    public NlcMemberRepositoryInterface $nlc_member_repository;
    public NpcMemberRepositoryInterface $npc_member_repository;
    public NstOrderRepositoryInterface $nst_order_repository;
    public ReevaOrderRepositoryInterface $reeva_order_repository;

    /**
     * @param UserRepositoryInterface $user_repository
     * @param NlcMemberRepositoryInterface $nlc_member_repository
     * @param NpcMemberRepositoryInterface $npc_member_repository
     * @param NstOrderRepositoryInterface $nst_order_repository
     * @param ReevaOrderRepositoryInterface $reeva_order_repository
     */
    public function __construct(UserRepositoryInterface $user_repository, NlcMemberRepositoryInterface $nlc_member_repository, NpcMemberRepositoryInterface $npc_member_repository, NstOrderRepositoryInterface $nst_order_repository, ReevaOrderRepositoryInterface $reeva_order_repository)
    {
        $this->user_repository = $user_repository;
        $this->nlc_member_repository = $nlc_member_repository;
        $this->npc_member_repository = $npc_member_repository;
        $this->nst_order_repository = $nst_order_repository;
        $this->reeva_order_repository = $reeva_order_repository;
    }


    public function createFromUser(User $user): string
    {
        return JWT::encode(
            [
                'user_id' => $user->getId()->toString(),
                'exp' => (new DateTime())->add(new DateInterval('P2D'))->getTimestamp()
            ],
            config('app.jwt_key'),
            'HS256'
        );
    }

    /**
     * @throws Exception
     */
    public function decode(string $jwt): SchAccount
    {
        try {
            $jwt = JWT::decode(
                $jwt,
                new Key(config('app.jwt_key'), 'HS256')
            );
        } catch (ExpiredException $e) {
            SchematicsException::throw('Session has expired', 902);
        } catch (SignatureInvalidException $e) {
            SchematicsException::throw('Session signature is invalid', 903);
        } catch (UnexpectedValueException $e) {
            SchematicsException::throw('Unexpected Session format', 907);
        }
        $user = $this->user_repository->find(new UserId($jwt->user_id));
        if (!$user) {
            SchematicsException::throw("User not found!", 1500);
        }
        $npc_member = $this->npc_member_repository->findByUserId($user->getId());
        $nlc_member = $this->nlc_member_repository->findByUserId($user->getId());
        $nst_order = $this->nst_order_repository->findByUserId($user->getId());
        $reeva_order = $this->reeva_order_repository->findByUserId($user->getId());
        return new SchAccount(
            new UserId($jwt->user_id),
            $npc_member?->getId(),
            $nlc_member?->getId(),
            $nst_order?->getId(),
            $reeva_order?->getId()
        );
    }
}
