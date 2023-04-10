<?php

namespace App\Http\Controllers;

use App\Core\Application\Service\PostFeedback\PostFeedbackRequest;
use App\Core\Application\Service\PostFeedback\PostFeedbackService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class WebFeedbackController extends Controller
{
    /**
     * @throws Throwable
     */
    public function postFeedback(Request $request, PostFeedbackService $service)
    {
        $input = new PostFeedbackRequest(
            $request->input('nama_sekolah'),
            $request->input('kepuasan'),
            $request->input('babak_soal'),
            $request->input('babak_game'),
            $request->input('kendala'),
            $request->input('kesan'),
            $request->input('kritik_saran'),
            $request->input('nama_ketua'),
            $request->input('nama_anggota_1'),
            $request->input('nama_anggota_2')
        );
        DB::beginTransaction();
        try {
            $service->execute($input, $request->get('account'));
        } catch (Throwable $exception) {
            DB::rollBack();
            throw $exception;
        }
        DB::commit();
        return redirect()->away('https://schematics.its.ac.id/dashboard/');
    }
}
