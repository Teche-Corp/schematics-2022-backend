<?php

namespace App\Core\Application\Service\UploadFileNlc;

use Illuminate\Http\UploadedFile;

class UploadFileNlcRequest
{
    private ?UploadedFile $bukti_vaksin;
    private ?UploadedFile $bukti_poster;
    private ?UploadedFile $bukti_twibbon;

    /**
     * @param UploadedFile|null $bukti_vaksin
     * @param UploadedFile|null $bukti_poster
     * @param UploadedFile|null $bukti_twibbon
     */
    public function __construct(?UploadedFile $bukti_vaksin, ?UploadedFile $bukti_poster, ?UploadedFile $bukti_twibbon)
    {
        $this->bukti_vaksin = $bukti_vaksin;
        $this->bukti_poster = $bukti_poster;
        $this->bukti_twibbon = $bukti_twibbon;
    }

    /**
     * @return UploadedFile|null
     */
    public function getBuktiVaksin(): ?UploadedFile
    {
        return $this->bukti_vaksin;
    }

    /**
     * @return UploadedFile|null
     */
    public function getBuktiPoster(): ?UploadedFile
    {
        return $this->bukti_poster;
    }

    /**
     * @return UploadedFile|null
     */
    public function getBuktiTwibbon(): ?UploadedFile
    {
        return $this->bukti_twibbon;
    }
}
