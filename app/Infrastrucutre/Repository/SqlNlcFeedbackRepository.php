<?php

namespace App\Infrastrucutre\Repository;

use App\Core\Domain\Models\NLC\Feedback\NlcFeedback;
use App\Core\Domain\Models\NLC\Feedback\NlcFeedbackId;
use App\Core\Domain\Models\NLC\Team\NlcTeamId;
use App\Core\Domain\Repository\NlcFeedbackRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;

class SqlNlcFeedbackRepository implements NlcFeedbackRepositoryInterface
{

    /**
     * @throws Exception
     */
    public function getByTeamId(NlcTeamId $nlc_team_id): array
    {
        $rows = DB::table('nlc_feedback')->where('nlc_team_id', $nlc_team_id->toString())->get();
        $nlc_feedbacks = [];
        foreach ($rows as $row) {
            $nlc_feedbacks[] = $this->constructFromRow($row);
        }
        return $nlc_feedbacks;
    }

    /**
     * @throws Exception
     */
    private function constructFromRow(object $row): NlcFeedback
    {
        return new NlcFeedback(
            new NlcFeedbackId($row->id),
            new NlcTeamId($row->nlc_team_id),
            $row->nama_sekolah,
            $row->tingkat_kepuasan,
            $row->babak_soal,
            $row->babak_game,
            $row->terdapat_kendala,
            $row->kesan,
            $row->kritik_saran,
            $row->nama_ketua,
            $row->nama_anggota_1,
            $row->nama_anggota_2
        );
    }

    public function insert(NlcFeedback $feedback): void
    {
        DB::table('nlc_feedback')->insert([
            'id' => $feedback->getId()->toString(),
            'nlc_team_id' => $feedback->getNlcTeamId()->toString(),
            'nama_ketua' => $feedback->getNamaKetua(),
            'nama_anggota_1' => $feedback->getNamaAnggota1(),
            'nama_anggota_2' => $feedback->getNamaAnggota2(),
            'nama_sekolah' => $feedback->getNamaSekolah(),
            'tingkat_kepuasan' => $feedback->getTingkatKepuasan(),
            'babak_game' => $feedback->getBabakGame(),
            'babak_soal' => $feedback->getBabakSoal(),
            'terdapat_kendala' => $feedback->isTerdapatKendala(),
            'kesan' => $feedback->getKesan(),
            'kritik_saran' => $feedback->getKritikSaran(),
        ]);
    }
}
