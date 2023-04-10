<?php

namespace App\Core\Application\Service\UploadFileNlc;

use App\Core\Domain\Models\NLC\Member\NlcMemberStatus;
use App\Core\Domain\Models\SchAccount;
use App\Core\Domain\Repository\NlcMemberRepositoryInterface;
use App\Exceptions\SchematicsException;
use Exception;
use Illuminate\Support\Facades\Storage;

class UploadFileNlcService
{
    private NlcMemberRepositoryInterface $nlc_member_repository;

    /**
     * @param NlcMemberRepositoryInterface $nlc_member_repository
     */
    public function __construct(NlcMemberRepositoryInterface $nlc_member_repository)
    {
        $this->nlc_member_repository = $nlc_member_repository;
    }

    /**
     * @throws Exception
     */
    public function execute(UploadFileNlcRequest $request, SchAccount $account)
    {
        $nlc_member = $this->nlc_member_repository->findByUserId($account->getUserId());

        if (!$nlc_member) {
            SchematicsException::throw("akun nlc not found", 9003);
        }
        if ($request->getBuktiPoster()) {
            $path = Storage::putFileAs("NLC/Member", $request->getBuktiPoster(), 'anggota_poster_'.$account->getUserId()->toString());
            if (!$path) SchematicsException::throw("gagal mengupload file poster", 9000);
            $nlc_member->setBuktiPosterUrl($path);
        }
        if ($request->getBuktiTwibbon()) {
            $path = Storage::putFileAs("NLC/Member", $request->getBuktiTwibbon(), 'anggota_twibbon_'.$account->getUserId()->toString());
            if (!$path) SchematicsException::throw("gagal mengupload file twibbon", 9001);
            $nlc_member->setBuktiTwibbonUrl($path);
        }
        if ($request->getBuktiVaksin()) {
            $path = Storage::putFileAs("NLC/Member", $request->getBuktiVaksin(), 'anggota_vaksin_'.$account->getUserId()->toString());
            if (!$path) SchematicsException::throw("gagal mengupload file vaksin", 9002);
            $nlc_member->setBuktiVaksinUrl($path);
        }
        $nlc_member->setStatus(NlcMemberStatus::ACTIVE);
        $this->nlc_member_repository->persist($nlc_member);
    }
}
