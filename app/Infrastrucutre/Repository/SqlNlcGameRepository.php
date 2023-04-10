<?php

namespace App\Infrastrucutre\Repository;

use Exception;
use Illuminate\Support\Facades\DB;
use App\Core\Domain\Models\NLC\Game\NlcGame;
use App\Core\Domain\Repository\NlcGameRepositoryInterface;

class SqlNlcGameRepository implements NlcGameRepositoryInterface
{
    /**
     * @throws Exception
     */
    public function findByAccountId(string $account_id): ?NlcGame
    {
        $row = DB::connection('nlc_game')->table('players')->where('account_id', $account_id)->first();

        if (!$row) return null;

        return $this->constructFromRows($row);
    }

    public function constructFromRows($row): NlcGame
    {
        return new NlcGame(
            $row->player_id,
            $row->remaining_coins,
            $row->remaining_hours,
            $row->discard_cards_count,
            $row->scores,
            $row->map_id
        );
    }
}
