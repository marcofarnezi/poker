<?php
namespace App\Service\Load;

/**
 * Class PokerLoad
 * @package App\Service\Load
 */
class PokerHandsLoad extends LoadFileAbstract
{
    /**
     * @var int
     */
    protected $card_hands;

    /**
     * @return string
     */
    protected function path(): string
    {
        return realpath(__DIR__ . '/../../../data/hands.txt');
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function toArray(): array
    {
        $rounds = $this->loadRounds();
        return $this->loadHands($rounds);
    }

    /**
     * @return array
     */
    protected function loadRounds(): array
    {
        return explode("\n", $this->file_content);
    }

    /**
     * @param array $rounds
     * @return array
     * @throws \Exception
     */
    protected function loadHands(array $rounds): array
    {
        $hands = [];
        foreach ($rounds as $round) {
            if (! empty($round)) {
                $hands[] = $this->splitHands($round);
            }
        }
        return $hands;
    }

    /**
     * @param string $round
     * @return array
     * @throws \Exception
     */
    protected function splitHands(string $round): array
    {
        $cards = explode(' ', $round);
        if (count($cards) % $this->card_hands != 0) {
            throw new \Exception("Card amount is wrong");
        }
        $hands = [];
        for ($i = 0; $i < (count($cards) / $this->card_hands); $i ++) {
            $hands[] = array_slice($cards, $i * $this->card_hands, $this->card_hands);
        }

        return $hands;
    }

    /**
     * @param int $cards_hand
     */
    public function setCardsHand(int $cards_hand)
    {
        $this->card_hands = $cards_hand;
    }
}