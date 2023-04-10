<?php

namespace App\Core\Application\Service\ResetPassword;

use App\Core\Domain\Models\Email;
use App\Core\Domain\Repository\UserRepositoryInterface;
use App\Exceptions\SchematicsException;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use function validator;

class ResetPasswordService
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
    public function execute(ResetPasswordRequest $request)
    {
        $decrypted_object = Crypt::decrypt($request->getToken());
        /*
            token = {
                'email'
                'exp'
            }
        */
        $this->validateToken($decrypted_object, $request->getToken());

        $user = $this->user_repository->findByEmail(new Email($decrypted_object['email']));
        if (!$user) {
            SchematicsException::throw("user tidak ketemu", 1006, 404);
        }
        $user->beginVerification()
            ->checkEmail($decrypted_object['email'])
            ->verify();

        $user->changePassword($request->getNewPassword());
        $this->user_repository->persist($user);
        $this->invalidateToken($decrypted_object);
    }

    /**
     * @throws Exception
     */
    private function validateToken($decoded_token, string $token)
    {
        validator($decoded_token, [
            'exp' => 'required',
            'email' => 'required|email',
        ])->validate();
        if ($decoded_token['exp'] < (new DateTime())->getTimestamp())
            SchematicsException::throw("Request reset password telah kadaluarsa", 1011);
        if (!$this->isValid($decoded_token['email'], $token))
            SchematicsException::throw("token tidak valid!", 1012);
    }

    private function isValid(string $email, string $token): bool
    {
        $row = DB::table('password_resets')->where('email', $email)->first();

        if (!$row || ($row->token !== $token) || $row->in_use != true) {
            return false;
        }
        return true;
    }

    private function invalidateToken($decoded_token): void
    {
        DB::table('password_resets')->where('email', $decoded_token['email'])->update([
            'in_use' => false
        ]);
    }
}
