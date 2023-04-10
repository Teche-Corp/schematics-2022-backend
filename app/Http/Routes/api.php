<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CitiesController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\NlcController;
use App\Http\Controllers\NpcController;
use App\Http\Controllers\NstController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\ProvincesController;
use App\Http\Controllers\ReevaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoucherController;
use App\Http\Middleware\HeaderMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('hello', function () {
    return response()->json();
});

Route::middleware([HeaderMiddleware::class, 'midtrans'])->group(
    function () {
        Route::post('/handle_transaction', [MidtransController::class, 'handlePayment']);
    }
);

Route::post('/create_user', [UserController::class, 'createUser']);
Route::post('/login_user', [UserController::class, 'loginUser']);

Route::post('/forgot_password', [UserController::class, 'forgotPassword']);
Route::post('/reset_password', [UserController::class, 'resetPassword']);

Route::get('/scoreboard_warmup', [NlcController::class, 'scoreboardWarmup']);
Route::get('/scoreboard_penyisihan', [NlcController::class, 'scoreboardPenyisihan']);

Route::middleware(['iam'])->group(
    function () {
        Route::get('test', function(){
            return response()->json([
                "success" => true
            ]);
        });
        Route::post('/register_npc_ketua', [NpcController::class, 'createNpcTeam']);
        Route::post('/register_nlc_ketua', [NlcController::class, 'createNlcTeam']);
        Route::post('/register_npc_anggota', [NpcController::class, 'registerNpcAnggota']);
        Route::post('/register_nlc_anggota', [NlcController::class, 'registerNlcAnggota']);
        Route::post('/upload_nlc_berkas', [NlcController::class, 'uploadBerkasNlc']);
        Route::post('/upload_nlc_bingo', [NlcController::class, 'uploadBingoNlc']);

        Route::get('/my_nlc', [UserController::class, 'getNlcTeam'])->name('get_nlc_team');
        Route::get('/my_npc', [UserController::class, 'getNpcTeam'])->name('get_npc_team');
        Route::get('/my_nst', [UserController::class, 'getNstOrder'])->name('get_nst_order');
        Route::get('/my_reeva', [UserController::class, 'getReevaOrder'])->name('get_reeva_order');


        Route::post('/create_pembayaran', [PembayaranController::class, 'createPembayaran']);

        Route::post('/order_nst_ticket', [NstController::class, 'orderNstTicket']);
        Route::post('/order_reeva_ticket', [ReevaController::class, 'orderReevaTicket']);

        Route::get('/me', [UserController::class, 'me']);
        Route::post('/user_edit', [UserController::class, 'user_edit']);

        Route::post('/request_payment_link_nst', [MidtransController::class, 'requestPaymentLinkNst']);
        Route::post('/request_payment_link_reeva', [MidtransController::class, 'requestPaymentLinkReeva']);

        Route::post('/check_voucher', [VoucherController::class, 'checkVoucherCode'])->name('check_voucher_code');

        Route::get('/nlc_game', [NlcController::class, 'nlc_game']);
    }
);
Route::get('/stream_image', [ImageController::class, 'streamImage']);

Route::get('/provinces', [ProvincesController::class, 'provinces']);

Route::middleware(['iam', 'admin'])->group(
    function () {
        Route::get('/admin_get_npc_team', [AdminController::class, 'getNpcTeam']);
        Route::get('/admin_get_nlc_team', [AdminController::class, 'getNlcTeam']);
        Route::post('/admin_verify_pembayaran', [AdminController::class, 'verifyPembayaran']);
        Route::post('/admin_verify_nlc_member', [AdminController::class, 'verifyNlcMember']);
        Route::post('/admin_verify_npc_member', [AdminController::class, 'verifyNpcMember']);
        Route::get('/admin_get_list_pembayaran_npc_junior', [AdminController::class, 'getListPembayaranNpcJunior']);
        Route::get('/admin_get_list_pembayaran_npc_senior', [AdminController::class, 'getListPembayaranNpcSenior']);
        Route::get('/admin_get_list_pembayaran_npc', [AdminController::class, 'getListPembayaranNpc']);
        Route::get('/admin_get_list_pembayaran_nlc', [AdminController::class, 'getListPembayaranNlc']);
        Route::get('/admin_get_list_pembayaran_nst', [AdminController::class, 'getListPembayaranNst']);
        Route::get('/admin_get_list_pembayaran_reeva', [AdminController::class, 'getListPembayaranReeva']);
        Route::get('/admin_list_nlc_team', [AdminController::class, 'listNlcTeam']);
        Route::get('/admin_list_npc_junior_team', [AdminController::class, 'listNpcJuniorTeam']);
        Route::get('/admin_list_npc_senior_team', [AdminController::class, 'listNpcSeniorTeam']);
        Route::get('/admin_get_detail_pembayaran', [AdminController::class, 'getDetailPembayaran']);
        Route::post('/admin_detail_reeva_ticket', [AdminController::class, 'getDetailTicketReeva']);
        Route::post('/admin_use_reeva_ticket', [AdminController::class, 'useReevaTicket']);
    }
);
Route::get('/cities/{province_id}', [CitiesController::class, 'cities']);
