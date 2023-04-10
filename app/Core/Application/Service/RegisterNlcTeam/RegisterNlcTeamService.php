<?php

namespace App\Core\Application\Service\RegisterNlcTeam;

use App\Core\Domain\Models\NLC\Member\NlcMember;
use App\Core\Domain\Models\NLC\Member\NlcMemberId;
use App\Core\Domain\Models\NLC\Member\NlcMemberStatus;
use App\Core\Domain\Models\NLC\Member\NlcMemberType;
use App\Core\Domain\Models\NLC\Team\NlcTeam;
use App\Core\Domain\Models\NLC\Team\NlcTeamStatus;
use App\Core\Domain\Models\SchAccount;
use App\Core\Domain\Repository\NlcMemberRepositoryInterface;
use App\Core\Domain\Repository\NlcTeamRepositoryInterface;
use App\Core\Domain\Repository\VoucherRepositoryInterface;
use App\Core\Domain\Service\TeamReferralCodeFactoryInterface;
use App\Core\Domain\Service\UniquePaymentCodeInterface;
use App\Exceptions\SchematicsException;
use Exception;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

class RegisterNlcTeamService
{
    private NlcTeamRepositoryInterface $nlc_team_repository;
    private NlcMemberRepositoryInterface $nlc_member_repository;
    private TeamReferralCodeFactoryInterface $referral_code_factory;
    private UniquePaymentCodeInterface $unique_payment;
    private VoucherRepositoryInterface $voucher_repository;

    /**
     * @param NlcTeamRepositoryInterface $nlc_team_repository
     * @param NlcMemberRepositoryInterface $nlc_member_repository
     * @param TeamReferralCodeFactoryInterface $referral_code_factory
     * @param VoucherRepositoryInterface $voucher_repository
     */
    public function __construct(NlcTeamRepositoryInterface $nlc_team_repository, NlcMemberRepositoryInterface $nlc_member_repository, TeamReferralCodeFactoryInterface $referral_code_factory, UniquePaymentCodeInterface $unique_payment, VoucherRepositoryInterface $voucher_repository)
    {
        $this->nlc_team_repository = $nlc_team_repository;
        $this->nlc_member_repository = $nlc_member_repository;
        $this->referral_code_factory = $referral_code_factory;
        $this->unique_payment = $unique_payment;
        $this->voucher_repository = $voucher_repository;
    }

    /**
     * @throws Exception
     */
    public function execute(RegisterNlcTeamRequest $request, SchAccount $account)
    {
        if ($account->getNlcMemberId()) {
            SchematicsException::throw("user sudah mempunyai team nlc", 2005);
        }

        $referral_code = $this->referral_code_factory->createReferralCodeFromRepository($this->nlc_team_repository);
        $biaya = (NlcTeam::getBaseBiaya() + $unique_payment_code = $this->unique_payment->getByEventType('nlc_code')); 
        if($request->getKodeVoucher()){
            $voucher = $this->voucher_repository->findByKode($request->getKodeVoucher());
            $biaya = $biaya - $voucher->getPotongan();
            $voucher->useVoucher();
            $this->voucher_repository->persist($voucher);
        }
        $nlc_team = NlcTeam::create(
            $referral_code,
            $request->getNamaTeam(),
            $request->getAsalSekolah(),
            $request->getNamaGuruPendamping(),
            $request->getNoTelpGuruPendamping(),
            $request->getRegion(),
            $request->getIdKota(),
            $biaya,
            $unique_payment_code,
            $request->getKodeVoucher()
        );

        if ($request->getSurat()->getSize() > 1048576) {
            SchematicsException::throw("surat harus dibawah 1Mb", 2000);
        }

        // if ($request->getBuktiTwibbon() && $request->getBuktiTwibbon()->getSize() > 1048576) {
        //     SchematicsException::throw("bukti twibbon harus dibawah 1Mb", 2001);
        // }

        // if ($request->getBuktiPoster() && $request->getBuktiPoster()->getSize() > 1048576) {
        //     SchematicsException::throw("bukti poster harus dibawah 1Mb", 2002);
        // }

        $surat_path = Storage::putFileAs("NLC/Member", $request->getSurat(), 'ketua_surat_'.$account->getUserId()->toString());
        // $poster_path = Storage::putFileAs("NLC/Member", $request->getBuktiPoster(), 'ketua_poster_'.$account->getUserId()->toString());
        // $twibbon_path = Storage::putFileAs("NLC/Member", $request->getBuktiTwibbon(), 'ketua_twibbon_'.$account->getUserId()->toString());
        // $bukti_vaksin_path = Storage::putFileAs("NLC/Member", $request->getBuktiVaksin(), 'ketua_vaksin'.$account->getUserId()->toString());

        // if (!$surat_path || !$poster_path || !$twibbon_path || $bukti_vaksin_path) {
        //     SchematicsException::throw("upload file gagal", 2060);
        // }

        if (!$surat_path) {
            SchematicsException::throw("upload file gagal", 2060);
        }

        
        $nlc_ketua = NlcMember::create(
            new NlcMemberId(Uuid::uuid4()),
            NlcMemberType::KETUA,
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

        $this->nlc_team_repository->persist($nlc_team);
        $this->nlc_member_repository->persist($nlc_ketua);

        return new RegisterNlcTeamResponse($nlc_team->getReferralCode()->getCode());
    }
}

