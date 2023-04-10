<?php

namespace App\Core\Application\Service\ForgotPassword;

use App\Core\Application\Mail\ForgotPasswordEmailHandler;
use App\Core\Domain\Models\Email;
use App\Core\Domain\Repository\UserRepositoryInterface;
use App\Exceptions\SchematicsException;
use DateInterval;
use DateTime;
use DateTimeZone;
use Exception;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class ForgotPasswordService
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
     * @throws ValidationException
     * @throws Exception
     */
    public function execute(ForgotPasswordRequest $request)
    {
        $user = $this->user_repository->findByEmail(new Email($request->getEmail()));
        if (!$user) {
            SchematicsException::throw("tidak ada user yang terdaftar dengan email ini", 10000, 404);
        }
        if ($token = DB::table('password_resets')->where('email', $user->getEmail()->toString())->first()) {
            if ($token->in_use) {
                SchematicsException::throw("user sudah mengirim email lupa password", 121212, 403);
            }
            if (
                (new DateTime($token->created_at, new DateTimeZone('Asia/Jakarta')))->add(new DateInterval('PT30M'))
                >= (new DateTime())->setTimezone(new DateTimeZone('Asia/Jakarta'))
            ) {
                SchematicsException::throw("cooldown lupa password 30 menit", 121213, 403);
            }
        }
        $token = Crypt::encrypt(
            [
                'email' => $user->getEmail()->toString(),
                'exp' => (new DateTime())->add(new DateInterval('PT30M'))->getTimestamp()
            ]
        );

        DB::table('password_resets')->upsert(
            [
                'email' => $user->getEmail()->toString(),
                'token' => $token,
                'in_use' => true,
                'created_at' => (new DateTime('', new DateTimeZone('Asia/Jakarta')))
            ],
            'email'
        );

        Mail::to($user->getEmail()->toString())
            ->send(new ForgotPasswordEmailHandler($token));
    }
}
