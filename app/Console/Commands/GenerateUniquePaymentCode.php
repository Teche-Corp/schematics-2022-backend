<?php

namespace App\Console\Commands;

use App\Core\Domain\Models\NLC\Team\NlcTeam;
use App\Core\Domain\Models\NPC\Team\NpcKategori;
use App\Core\Domain\Models\NPC\Team\NpcTeam;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class GenerateUniquePaymentCode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:generate-unique-payment-code';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command is used for generating unique payemnt code';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $baseNLC = NlcTeam::getBaseBiaya();
        $latestNLCBiaya = DB::table('nlc_team')->latest('created_at')->first(['biaya']);
        if(!$latestNLCBiaya){
            $latestNLCBiaya = $baseNLC + 1;
        } else {
            $latestNLCBiaya = $latestNLCBiaya->biaya;
        }
        $latestNLCCode = $latestNLCBiaya - $baseNLC;
        Cache::put('nlc_code', $latestNLCCode);

        $baseNPCJunior = NpcTeam::BASE_PRICE[NpcKategori::JUNIOR->value];
        $latestNPCJuniorBiaya = DB::table('npc_team')->where('kategori', NpcKategori::JUNIOR->value)->latest('created_at')->first(['biaya']);
        if(!$latestNPCJuniorBiaya){
            $latestNPCJuniorBiaya = $baseNPCJunior + 1;
        } else {
            $latestNPCJuniorBiaya = $latestNPCJuniorBiaya->biaya;
        }
        $latestNPCJuniorCode = $latestNPCJuniorBiaya - $baseNPCJunior;
        Cache::put('npc_junior_code', $latestNPCJuniorCode);

        $baseNPCSenior = NpcTeam::BASE_PRICE[NpcKategori::SENIOR->value];
        $latestNPCSeniorBiaya = DB::table('npc_team')->where('kategori', NpcKategori::SENIOR->value)->latest('created_at')->first(['biaya']);
        if(!$latestNPCSeniorBiaya){
            $latestNPCSeniorBiaya = $baseNPCSenior + 1;
        } else {
            $latestNPCSeniorBiaya = $latestNPCSeniorBiaya->biaya;
        }
        $latestNPCSeniorCode = $latestNPCSeniorBiaya - $baseNPCSenior;
        Cache::put('npc_senior_code', $latestNPCSeniorCode);
        return 1;
    }
}
