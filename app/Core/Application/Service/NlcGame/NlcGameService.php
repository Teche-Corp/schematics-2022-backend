<?php

namespace App\Core\Application\Service\NlcGame;

use Exception;
use App\Core\Domain\Models\SchAccount;
use App\Exceptions\SchematicsException;
use App\Core\Domain\Repository\UserRepositoryInterface;
use App\Core\Application\Service\NlcGame\NlcGameResponse;
use App\Core\Domain\Repository\NlcGameAccountRepositoryInterface;
use App\Core\Domain\Repository\NlcGameRepositoryInterface;

class NlcGameService
{
    private UserRepositoryInterface $user_repository;
    private NlcGameAccountRepositoryInterface $nlc_game_account_repository;
    private NlcGameRepositoryInterface $nlc_game_repository;

    /**
     * @param NlcGameRepositoryInterface $nlc_game_repository
     */
    public function __construct(UserRepositoryInterface $user_repository, NlcGameAccountRepositoryInterface $nlc_game_account_repository, NlcGameRepositoryInterface $nlc_game_repository)
    {
        $this->user_repository = $user_repository;
        $this->nlc_game_account_repository = $nlc_game_account_repository;
        $this->nlc_game_repository = $nlc_game_repository;
    }

    /**
     * @throws Exception
     */
    public function execute(SchAccount $account): NlcGameResponse
    {
        $user = $this->user_repository->find($account->getUserId());
        if (!$user) {
            SchematicsException::throw("user tidak ditemukan", 1006, 404);
        }
        $account = $this->nlc_game_account_repository->findByEmail($user->getEmail());
        $player = $this->nlc_game_repository->findByAccountId($account->getAccountId());
        return new NlcGameResponse(
            $player->getPlayerId(),
            $player->getRemainingCoins(),
            $player->getRemainingHours(),
            $player->getDiscardCardsCount(),
            $player->getScores(),
            $player->getMapId()
        );
    }
}
