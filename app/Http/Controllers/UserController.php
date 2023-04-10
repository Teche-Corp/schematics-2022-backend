<?php

namespace App\Http\Controllers;


use Exception;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Exceptions\SchematicsException;
use App\Core\Application\Service\Me\MeService;
use App\Core\Application\Service\LoginUser\LoginUserRequest;
use App\Core\Application\Service\LoginUser\LoginUserService;
use App\Core\Application\Service\GetNstOrder\GetNstOrderRequest;
use App\Core\Application\Service\GetNstOrder\GetNstOrderService;
use App\Core\Application\Service\GetNlcTeamUser\GetNlcTeamRequest;
use App\Core\Application\Service\GetNlcTeamUser\GetNlcTeamService;
use App\Core\Application\Service\GetNpcTeamUser\GetNpcTeamRequest;
use App\Core\Application\Service\GetNpcTeamUser\GetNpcTeamService;
use App\Core\Application\Service\RegisterUser\RegisterUserRequest;
use App\Core\Application\Service\RegisterUser\RegisterUserService;
use App\Core\Application\Service\ForgotPassword\ForgotPasswordRequest;
use App\Core\Application\Service\ForgotPassword\ForgotPasswordService;
use App\Core\Application\Service\ResetPassword\ResetPasswordRequest;
use App\Core\Application\Service\ResetPassword\ResetPasswordService;

use App\Core\Application\Service\GetReevaOrder\GetReevaOrderRequest;
use App\Core\Application\Service\GetReevaOrder\GetReevaOrderService;
use App\Core\Application\Service\UserEdit\UserEditRequest;
use App\Core\Application\Service\UserEdit\UserEditService;


class UserController extends Controller
{

    /**
     * @throws Exception
     */
    public function createUser(Request $request, RegisterUserService $service): JsonResponse
    {
        $request->validate([
            'email' => 'unique:user,email|email',
            'password' => 'min:8|max:64|string',
            'name' => 'min:8|max:128|string',
            'no_telp' => 'min:10|max:15|string'
        ]);

        $input = new RegisterUserRequest(
            $request->input('email'),
            $request->input('no_telp'),
            $request->input('name'),
            $request->input('password')
        );

        DB::beginTransaction();
        try {
            $service->execute($input);
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
        DB::commit();
        return $this->success();
    }

    /**
     * @throws Exception
     */
    public function loginUser(Request $request, LoginUserService $service): JsonResponse
    {
        $input = new LoginUserRequest(
            $request->input('email'),
            $request->input('password')
        );
        $response = $service->execute($input);
        return $this->successWithData($response);
    }

    /**
     * @throws SchematicsException
     */
    public function getNlcTeam(Request $request, GetNlcTeamService $service): JsonResponse
    {
        $input = new GetNlcTeamRequest($request->get('account'));
        $response = $service->execute($input);
        return $this->successWithData($response);
    }

    /**
     * @throws SchematicsException
     */
    public function getNpcTeam(Request $request, GetNpcTeamService $service): JsonResponse
    {
        $input = new GetNpcTeamRequest($request->get('account'));
        $response = $service->execute($input);
        return $this->successWithData($response);
    }

    /**
     * @throws SchematicsException
     */
    public function getNstOrder(Request $request, GetNstOrderService $service): JsonResponse
    {
        $input = new GetNstOrderRequest($request->get('account'));
        $response = $service->execute($input);
        return $this->successWithData($response);
    }

    /**
     * @throws SchematicsException
     */
    public function getReevaOrder(Request $request, GetReevaOrderService $service): JsonResponse
    {
        $input = new GetReevaOrderRequest($request->get('account'));
        $response = $service->execute($input);
        return $this->successWithData($response);
    }

    /**
     * @throws Exception
     */
    public function me(Request $request, MeService $service): JsonResponse
    {
        $response = $service->execute($request->get('account'));
        return $this->successWithData($response);
    }

    /**
     * @throws Exception
     */
    public function forgotPassword(Request $request, ForgotPasswordService $service): JsonResponse
    {
        $input = new ForgotPasswordRequest($request->input('email'));

        DB::beginTransaction();
        try {
            $service->execute($input);
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
        DB::commit();

        return $this->success();
    }

    /**
     * @throws Exception
     */
    public function resetPassword(Request $request, ResetPasswordService $service): JsonResponse
    {
        $input = new ResetPasswordRequest(
            $request->input('token'),
            $request->input('new_password')
        );

        DB::beginTransaction();
        try {
            $service->execute($input);
        } catch (Throwable $exception) {
            DB::rollBack();
            throw $exception;
        }
        DB::commit();
        return $this->success();
     }

    /**
     * @throws Exception
     */
    public function user_edit(Request $request, UserEditService $service): JsonResponse
    {
        $request->validate([
            'email' => 'unique:user,email|email',
            'password' => 'min:8|max:64|string',
            'name' => 'min:8|max:128|string',
            'no_telp' => 'min:10|max:15|string'
        ]);

        $input = new
        UserEditRequest(
            $request->input('email'),
            $request->input('no_telp'),
            $request->input('name'),
            $request->input('password')
        );
        $service->execute($input, $request->get('account'));

        return $this->success();
    }
}
