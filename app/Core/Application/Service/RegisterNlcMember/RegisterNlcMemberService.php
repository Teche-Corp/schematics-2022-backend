<?php

namespace App\Core\Application\Service\RegisterNlcMember;

use App\Core\Domain\Models\NLC\Member\NlcMember;
use App\Core\Domain\Models\NLC\Member\NlcMemberId;
use App\Core\Domain\Models\NLC\Member\NlcMemberType;
use App\Core\Domain\Models\ReferralCode;
use App\Core\Domain\Models\SchAccount;
use App\Core\Domain\Repository\NlcMemberRepositoryInterface;
use App\Core\Domain\Repository\NlcTeamRepositoryInterface;
use App\Exceptions\SchematicsException;
use Exception;
use Illuminate\Support\Facades\Storage;
use function count;

class RegisterNlcMemberService
{
    private NlcTeamRepositoryInterface $nlc_team_repository;
    private NlcMemberRepositoryInterface $nlc_member_repository;

    /**
     * @param NlcTeamRepositoryInterface $nlc_team_repository
     * @param NlcMemberRepositoryInterface $nlc_member_repository
     */
    public function __construct(NlcTeamRepositoryInterface $nlc_team_repository, NlcMemberRepositoryInterface $nlc_member_repository)
    {
        $this->nlc_team_repository = $nlc_team_repository;
        $this->nlc_member_repository = $nlc_member_repository;
    }

    /**
     * @throws Exception
     */
    public function execute(RegisterNlcMemberRequest $request, SchAccount $account)
    {
        $nlc_team = $this->nlc_team_repository->findByReferralCode(new ReferralCode($request->getKodeReferral()));

        if(!$nlc_team){
            SchematicsException::throw("Kode afiliasi tim tidak ditemukan", 2018);
        }

        $nlc_members = $this->nlc_member_repository->getByTeamId($nlc_team->getId());
        if (count($nlc_members) >= NlcMember::MAX_MEMBER) {
            SchematicsException::throw("jumlah member sudah mencapai batas", 1000);
        }

        // if ($nlc_team->getStatus() != NlcTeamStatus::ACTIVE) {
        //     SchematicsException::throw("tim belum menyelesaikan proses administrasi", 2015);
        // }

        if ($request->getSurat()->getSize() > 1048576) {
            SchematicsException::throw("surat harus dibawah 1Mb", 2010);
        }

        // if ($request->getBuktiTwibbon()->getSize() > 1048576) {
        //     SchematicsException::throw("bukti twibbon harus dibawah 1Mb", 2011);
        // }

        // if ($request->getBuktiPoster()->getSize() > 1048576) {
        //     SchematicsException::throw("bukti poster harus dibawah 1Mb", 2012);
        // }

        $surat_path = Storage::putFileAs("NLC/Member", $request->getSurat(), 'anggota_surat_'.$account->getUserId()->toString());
        // $poster_path = Storage::putFileAs("NLC/Member", $request->getBuktiPoster(), 'anggota_poster_'.$account->getUserId()->toString());
        // $twibbon_path = Storage::putFileAs("NLC/Member", $request->getBuktiTwibbon(), 'anggota_twibbon_'.$account->getUserId()->toString());
        // $bukti_vaksin_path = Storage::putFileAs("NLC/Member", $request->getBuktiVaksin(), 'anggota_vaksin'.$account->getUserId()->toString());

        // if (!$surat_path || !$poster_path || !$twibbon_path || !$bukti_vaksin_path) {
        //     SchematicsException::throw("upload file gagal", 2003);
        // }\
        if (!$surat_path) {
            SchematicsException::throw("upload file gagal", 2003);
        }

        $nlc_member = NlcMember::create(
            NlcMemberId::generate(),
            NlcMemberType::ANGGOTA,
            $nlc_team->getId(),
            $account->getUserId(),
            $request->getNisn(),
            $surat_path,
            null,
            null,
            $request->getNoTelp(),
            $request->getNoWa(),
            $request->getIdLine(),
            $request->getAlamat(),
            null,
            $request->getInfoSch(),
            $request->getJenisVaksin(),
            null
        );

        $this->nlc_member_repository->persist($nlc_member);
    }
}
