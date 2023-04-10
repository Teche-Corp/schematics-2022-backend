<?php

namespace App\Core\Domain\Models\Pembayaran;

class Pembayaran
{
    private PembayaranId $id;
    private StatusPembayaran $status_pembayaran;
    private TipePembayaran $tipe_pembayaran;
    private SubjectId $subject_id;
    private TipeBank $tipe_bank;
    private ?string $nama_rekening;
    private ?string $no_rekening;
    private string $bukti_bayar_url;

    /**
     * @param PembayaranId $id
     * @param StatusPembayaran $status_pembayaran
     * @param TipePembayaran $tipe_pembayaran
     * @param SubjectId $subject_id
     * @param TipeBank $tipe_bank
     * @param ?string $nama_rekening
     * @param ?string $no_rekening
     * @param string $bukti_bayar_url
     */
    public function __construct(PembayaranId $id, StatusPembayaran $status_pembayaran, TipePembayaran $tipe_pembayaran, SubjectId $subject_id, TipeBank $tipe_bank, ?string $nama_rekening, ?string $no_rekening, string $bukti_bayar_url)
    {
        $this->id = $id;
        $this->status_pembayaran = $status_pembayaran;
        $this->tipe_pembayaran = $tipe_pembayaran;
        $this->subject_id = $subject_id;
        $this->tipe_bank = $tipe_bank;
        $this->nama_rekening = $nama_rekening;
        $this->no_rekening = $no_rekening;
        $this->bukti_bayar_url = $bukti_bayar_url;
    }

    /**
     * @param string $bukti_bayar_url
     */
    public function setBuktiBayarUrl(string $bukti_bayar_url): void
    {
        $this->bukti_bayar_url = $bukti_bayar_url;
    }

    /**
     * @param StatusPembayaran $status_pembayaran
     */
    public function setStatusPembayaran(StatusPembayaran $status_pembayaran): void
    {
        $this->status_pembayaran = $status_pembayaran;
    }

    /**
     * @return PembayaranId
     */
    public function getId(): PembayaranId
    {
        return $this->id;
    }

    /**
     * @return StatusPembayaran
     */
    public function getStatusPembayaran(): StatusPembayaran
    {
        return $this->status_pembayaran;
    }

    /**
     * @return TipePembayaran
     */
    public function getTipePembayaran(): TipePembayaran
    {
        return $this->tipe_pembayaran;
    }

    /**
     * @return SubjectId
     */
    public function getSubjectId(): SubjectId
    {
        return $this->subject_id;
    }

    /**
     * @return TipeBank
     */
    public function getTipeBank(): TipeBank
    {
        return $this->tipe_bank;
    }

    /**
     * @return string
     */
    public function getNamaRekening(): ?string
    {
        return $this->nama_rekening;
    }

    /**
     * @return string
     */
    public function getNoRekening(): ?string
    {
        return $this->no_rekening;
    }

    /**
     * @return string
     */
    public function getBuktiBayarUrl(): string
    {
        return $this->bukti_bayar_url;
    }
}
