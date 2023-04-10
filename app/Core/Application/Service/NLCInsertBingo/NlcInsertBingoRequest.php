<?php

namespace App\Core\Application\Service\NLCInsertBingo;

use Illuminate\Http\UploadedFile;

class NlcInsertBingoRequest {
    private UploadedFile $bingo_file;

    /**
     * constructor
     *
     * @param UploadedFile $bingo_file
     */
    public function __construct(UploadedFile $bingo_file)
    {
        $this->bingo_file = $bingo_file;
    }

    /**
     * Get uploaded bingo
     *
     * @return UploadedFile
     */
    public function getBingoFile() : UploadedFile 
    {
        return $this->bingo_file;
    }
}