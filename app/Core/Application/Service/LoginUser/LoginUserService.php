<?php

namespace App\Core\Application\Service\LoginUser;

use App\Core\Domain\Models\Email;
use App\Core\Domain\Repository\UserRepositoryInterface;
use App\Core\Domain\Service\JwtManagerInterface;
use App\Exceptions\SchematicsException;
use Exception;

class LoginUserService
{
    private UserRepositoryInterface $user_repository;
    private JwtManagerInterface $jwt_factory;

    /**
     * @param UserRepositoryInterface $user_repository
     * @param JwtManagerInterface $jwt_factory
     */
    public function __construct(UserRepositoryInterface $user_repository, JwtManagerInterface $jwt_factory)
    {
        $this->user_repository = $user_repository;
        $this->jwt_factory = $jwt_factory;
    }

    /**
     * @throws Exception
     */
    public function execute(LoginUserRequest $request): LoginUserResponse
    {
        $user = $this->user_repository->findByEmail(new Email($request->getEmail()));
        if (!$user) {
            SchematicsException::throw("user tidak ketemu", 1006, 404);
        }
        $user->beginVerification()
            ->checkPassword($request->getPassword())
            ->verify();
        $token_jwt = $this->jwt_factory->createFromUser($user);
        return new LoginUserResponse($token_jwt);
    }
}
