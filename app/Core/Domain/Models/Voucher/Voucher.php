<?php

namespace App\Core\Domain\Models\Voucher;

use Carbon\Carbon;

class Voucher 
{
    private VoucherId $id;
    private string $kode;
    private int $potongan;
    private int $kuota;
    private int $region;
    private Carbon $start_time;
    private Carbon $end_time;
    private string $tipe;
    
    public function __construct(
        VoucherId $id,
        string $kode,
        int $potongan,
        int $kuota,
        int $region,
        string $start_time,
        string $end_time,
        string $tipe
    )
    {
        $start_time = Carbon::parse($start_time);
        $end_time = Carbon::parse($end_time);
        $this->id = $id;
        $this->kode = $kode;
        $this->potongan = $potongan;
        $this->kuota = $kuota;
        $this->region = $region;
        $this->start_time = $start_time;
        $this->end_time = $end_time;
        $this->tipe = $tipe;
    }

    public static function create(
        string $kode,
        int $potongan,
        int $kuota,
        int $region,
        string $start_time,
        string $end_time,
        string $tipe
    ) : self
    {
        return new self(
            VoucherId::generate(),
            $kode,
            $potongan,
            $kuota,
            $region,
            $start_time,
            $end_time,
            $tipe
        );
    }

    /**
     * Get the value of voucher_id
     * @return VoucherId
     */ 
    public function getId() : VoucherId
    {
        return $this->id;
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
     * Get the value of potongan
     * @return int
     */ 
    public function getPotongan() : int
    {
        return $this->potongan;
    }

    /**
     * Get the value of kuota
     * @return int
     */ 
    public function getKuota() : int
    {
        return $this->kuota;
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
     * Get the value of start_time
     * @return Carbon
     */ 
    public function getStartTime() : Carbon
    {
        return $this->start_time;
    }

    /**
     * Get the value of end_time
     * @return Carbon
     */ 
    public function getEndTime() : Carbon
    {
        return $this->end_time;
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
     * substract 1 kode voucher dari kuota
     *
     * @return void
     */
    public function useVoucher(int $n=1): void
    {
        $this->kuota = $this->kuota - $n;
    }
}