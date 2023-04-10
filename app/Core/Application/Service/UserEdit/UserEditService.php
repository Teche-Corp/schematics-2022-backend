<?php

namespace App\Core\Application\Service\UserEdit;

use App\Core\Domain\Models\Email;
use App\Core\Domain\Models\SchAccount;
use App\Core\Domain\Repository\UserRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Hash;

class UserEditService
{
    private UserRepositoryInterface $user_repository;

    /**
     * @param UserRepositoryInterface $user_repository
     */
    public function __construct(UserRepositoryInterface $user_repository)
    {
        $this->user_repository = $user_repository;
    }

    /**
     * @throws Exception
     */
    public function execute(UserEditRequest $request, SchAccount $account)
    {
        $user = $this->user_repository->find($account->getUserId());
        if ($request->getEmail()) $user->setEmail(new Email($request->getEmail()));
        if ($request->getName()) $user->setName($request->getName());
        if ($request->getNoTelp()) $user->setNoTelp($request->getNoTelp());
        if ($request->getPassword()) $user->setHashedPassword(Hash::make($request->getPassword()));

        $this->user_repository->persist($user);
    }
}
