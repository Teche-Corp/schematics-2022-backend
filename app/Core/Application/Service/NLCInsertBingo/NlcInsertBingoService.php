<?php

namespace App\Core\Application\Service\NLCInsertBingo;

use App\Core\Domain\Models\SchAccount;
use App\Core\Domain\Repository\NlcMemberRepositoryInterface;
use App\Exceptions\SchematicsException;
use Illuminate\Support\Facades\Storage;

class NlcInsertBingoService {
    private NlcMemberRepositoryInterface $nlc_member_repository;

    public function __construct(NlcMemberRepositoryInterface $nlc_member_repository)
    {
        $this->nlc_member_repository = $nlc_member_repository;
    }

    public function execute(NlcInsertBingoRequest $request, SchAccount $account){
        $member = $this->nlc_member_repository->findByUserId($account->getUserId());
        if(!$member) {
            SchematicsException::throw('member nlc team not found', 2041);
        }
        $path = Storage::putFileAs('NLC/Bingo', $request->getBingoFile(), 'anggota_bingo_'.$account->getUserId()->toString());
        if(!$path) {
            SchematicsException::throw('gagal menyimpan bukti bingo nlc member', 2044);
        }
        $member->setBingoFileUrl($path);
        $this->nlc_member_repository->persist($member);
    }
}