<?php

namespace App\Core\Application\Service\NlcGame;

use JsonSerializable;

class NlcGameResponse implements JsonSerializable
{
    private string $player_id;
    private int $remaining_coins;
    private string $remaining_hours;
    private int $discard_cards_count;
    private int $scores;
    private int $map_id;

    public function __construct(string $player_id, int $remaining_coins, string $remaining_hours, int $discard_cards_count, int $scores, int $map_id)
    {
        $this->player_id = $player_id;
        $this->remaining_coins = $remaining_coins;
        $this->remaining_hours = $remaining_hours;
        $this->discard_cards_count = $discard_cards_count;
        $this->scores = $scores;
        $this->map_id = $map_id;
    }

    public function jsonSerialize(): array
    {
        return [
            'player_id' => $this->player_id,
            'remaining_coins' => $this->remaining_coins,
            'remaining_hours' => $this->remaining_hours,
            'discard_cards_count' => $this->discard_cards_count,
            'scores' => $this->scores,
            'map_id' => $this->map_id
        ];
    }
}
