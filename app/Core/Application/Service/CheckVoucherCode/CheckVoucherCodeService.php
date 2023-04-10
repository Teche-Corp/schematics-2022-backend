<?php

namespace App\Core\Application\Service\CheckVoucherCode;

use App\Core\Domain\Repository\VoucherRepositoryInterface;
use App\Exceptions\SchematicsException;

class CheckVoucherCodeService {
    private VoucherRepositoryInterface $voucher_repository;

    public function __construct(VoucherRepositoryInterface $voucher_repository)
    {
        $this->voucher_repository = $voucher_repository;
    }

    public function execute(CheckVoucherCodeRequest $request){
        $voucher = $this->voucher_repository->findByKode($request->getKode());

        if(!$voucher) SchematicsException::throw("Kode voucher tidak ditemukan", 5224, 404);

        if($voucher->getKuota()==0) SchematicsException::throw("Kuota kode voucher telah habis", 5220, 400);
        
        if($request->getJumlah()){
            if ($voucher->getKode() != "COUPLE" && $voucher->getKode() != "GROUP") {
                if($voucher->getKuota() < $request->getJumlah()) SchematicsException::throw("Kuota kode voucher kurang dari jumlah tiket anda", 5220, 400);
            }
        }

        if(($voucher->getRegion()!==0&&$voucher->getRegion()!==$request->getRegion())) SchematicsException::throw("Region anda tidak berhak untuk menggunakan kode voucher ini", 5221, 400);

        if($voucher->getTipe()!==$request->getTipe()) SchematicsException::throw("Jenis lomba yang akan anda ikuti tidak berhak menggunakan kode promo ini", 5221, 400);

        return $voucher;
    }
}