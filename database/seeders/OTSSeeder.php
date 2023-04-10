<?php

namespace Database\Seeders;

use App\Core\Domain\Models\Email;
use App\Core\Domain\Models\NLC\Member\NlcMember;
use App\Core\Domain\Models\NLC\Member\NlcMemberId;
use App\Core\Domain\Models\NLC\Member\NlcMemberType;
use App\Core\Domain\Models\NLC\Team\NlcTeam;
use App\Core\Domain\Models\ReferralCode;
use App\Core\Domain\Models\User\User;
use App\Core\Domain\Models\User\UserType;
use App\Infrastrucutre\Repository\SqlNlcMemberRepository;
use App\Infrastrucutre\Repository\SqlNlcTeamRepository;
use App\Infrastrucutre\Repository\SqlUserRepository;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class OTSSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Seeding user
        $users = [];
        $userRecords = Reader::createFromPath(
            "database/seeders/csv/users_ots.csv",
            "r",
        );
        $userRecords->setDelimiter(",");
        $userRecords->setHeaderOffset(0);
        $userRepository = new SqlUserRepository;
        foreach ($userRecords as $userRecord) {
            $user = User::create(
                UserType::USER,
                new Email($userRecord['email']),
                $userRecord['no_telp'],
                $userRecord['name'],
                $userRecord['password']
            );

            DB::beginTransaction();
            try {
                $userRepository->persist($user);
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
            }
            $users[] = $user;
        }

        //Seeding NlcTeamPemanasan
        $nlcTeams = [];
        $teamsRecords = Reader::createFromPath(
            "database/seeders/csv/team_ots_seeder.csv",
            "r",
        );
        $teamsRecords->setDelimiter(",");
        $teamsRecords->setHeaderOffset(0);
        $teamRepository = new SqlNlcTeamRepository();
        foreach ($teamsRecords as $teamsRecord) {
            $team = NlcTeam::create(
                ReferralCode::generate(),
                $teamsRecord['nama_team'],
                $teamsRecord['asal_sekolah'],
                $teamsRecord['nama_guru_pendamping'],
                $teamsRecord['no_telp_guru_pendamping'],
                $teamsRecord['region'],
                $teamsRecord['id_kota'],
                $teamsRecord['biaya'],
                $teamsRecord['unique_payment_code'],
                null,
                $teamsRecord['username'],
                $teamsRecord['password']
            );
            DB::table('nlc_team')->insert([
                "id" => $team->getId()->toString(),
                "referral_code" => $team->getReferralCode()->getCode(),
                "status" => $team->getStatus()->value,
                "nama_team" => $team->getNamaTeam(),
                "asal_sekolah" => $team->getAsalSekolah(),
                "nama_guru_pendamping" => $team->getNamaGuruPendamping(),
                "no_telp_guru_pendamping" => $team->getNoTelpGuruPendamping(),
                "region" => $team->getRegion(),
                "id_kota" => $team->getIdKota(),
                "biaya" => $team->getBiaya(),
                "unique_payment_code" => $team->getUniquePaymentCode(),
                "kode_voucher" => $team->getKodeVoucher(),
                "email_lomba" => $team->getEmailLomba(),
                "password_lomba" => $team->getPasswordLomba()
            ]);
            $nlcTeams[] = $team;
        }

        //Seeding NlcMemberPemanasan
        $nlcMembers = [];
        $memberRepository = new SqlNlcMemberRepository();
        $memberRecords = Reader::createFromPath(
            "database/seeders/csv/ots_member.csv",
            "r",
        );
        $memberRecords->setDelimiter(",");
        $memberRecords->setHeaderOffset(0);
        foreach ($memberRecords as $memberRecord) {
            $nlcMember = NlcMember::create(
                NlcMemberId::generate(),
                ($memberRecord['member_type'] === 'ketua' ? NlcMemberType::KETUA : NlcMemberType::ANGGOTA),
                $nlcTeams[$memberRecord['team_id'] - 1]->getId(),
                $users[$memberRecord['user_id'] - 1]->getId(),
                $memberRecord['nisn'],
                $memberRecord['surat_url'],
                null,
                null,
                $users[$memberRecord['user_id'] - 1]->getNoTelp(),
                $users[$memberRecord['user_id'] - 1]->getNoTelp(),
                $memberRecord['id_line'],
                $memberRecord['alamat'],
                null,
                $memberRecord['info_sch'],
                null,
                null
            );
            $memberRepository->persist($nlcMember);
            $nlcMembers[] = $nlcMember;
        }
    }
}
