<?php

namespace App\Core\Domain\Models\Kota;

class Kota
{
    private int $id;
    private int $foreign;
    private string $name;

    /**
     * @param int $id
     * @param int $foreign
     * @param string $name
     */
    public function __construct(int $id, int $foreign, string $name)
    {
        $this->id = $id;
        $this->foreign = $foreign;
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getForeign(): int
    {
        return $this->foreign;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
