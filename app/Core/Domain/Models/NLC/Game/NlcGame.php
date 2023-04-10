<?php

namespace App\Core\Domain\Models\NLC\Game;

class NlcGame
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

    public static function create(string $player_id, int $remaining_coins, string $remaining_hours, int $discard_cards_count, int $scores, int $map_id): self
    {
        return new self(
            $player_id,
            $remaining_coins,
            $remaining_hours,
            $discard_cards_count,
            $scores,
            $map_id
        );
    }

    public function getPlayerId(): string
    {
        return $this->player_id;
    }

    public function getRemainingCoins(): int
    {
        return $this->remaining_coins;
    }

    public function getRemainingHours(): string
    {
        return $this->remaining_hours;
    }

    public function getDiscardCardsCount(): int
    {
        return $this->discard_cards_count;
    }
    public function getScores(): int
    {
        return $this->scores;
    }

    public function getMapId(): int
    {
        return $this->map_id;
    }
}
