<?php

namespace App\Core\Application\Service\CheckVoucherCode;

class CheckVoucherCodeRequest {
    private string $kode;
    private int $region;
    private string $tipe;
    private ?string $jumlah;

    /**
     * check voucher request constructor
     *
     * @param string $kode
     * @param integer $region
     * @param string $tipe
     * @param ?int $jumlah
     */
    public function __construct(
        string $kode,
        int $region,
        string $tipe,
        ?int $jumlah = null
    )
    {
        $this->kode = $kode;
        $this->region = $region;
        $this->tipe = $tipe;
        $this->jumlah = $jumlah;
    }

    /**
     * Get the value of kode
     * @return string
     */ 
    public function getKode() : string
    {
        return $this->kode;
    }

    /**
     * Get the value of region
     * @return int
     */ 
    public function getRegion() : int
    {
        return $this->region;
    }

    /**
     * Get the value of tipe
     * @return string
     */ 
    public function getTipe() : string
    {
        return $this->tipe;
    }

    /**
     * 
     * @return int|null
     */ 
    public function getJumlah() : ?int
    {
        return $this->jumlah;
    }
}