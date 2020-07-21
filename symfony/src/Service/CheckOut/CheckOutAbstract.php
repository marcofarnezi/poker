<?php
namespace App\Service\CheckOut;

use App\Service\Rule\RuleAbstraction;

/**
 * Class CheckOutAbstract
 * @package App\Service\CheckOut
 */
abstract class CheckOutAbstract
{
    /**
     * @var array
     */
    private $hands_list;

    /**
     * @var RuleAbstraction
     */
    private $rule;

    /**
     * @var int
     */
    private $value;

    /**
     * @var int
     */
    private $ranking;

    /**
     * CheckOutAbstract constructor.
     */
    public function __construct()
    {
        $this->getRule();
    }

    /**
     * @param array $hands
     */
    public function loadHands(array $hands)
    {
        $this->hands_list = $hands;
    }

    /**
     * @return array
     */
    public function checkHands(): array
    {
        $hands = [];
        foreach ($this->hands_list as $hand) {
            $cards = json_decode($hand->getCards());
            $round_id = $hand->getRound()->getId();
            $player_id = $hand->getPlayer()->getId();
            $hands[$round_id][$player_id] = [
                "cards" => $cards,
                "hand" => $this->checkHand($cards),
                "value" => $this->value,
                "ranking" => $this->ranking
            ];
        }

        return $hands;
    }

    /**
     * @param array $round
     * @return array
     */
    public function setChampion(array $round): array
    {
        $ranking = 0;
        $value = 0;
        $winner = 0;
        foreach ($round as $player_id => $hand) {
            if ($ranking < $hand['ranking']) {
                $ranking = $hand['ranking'];
                $value = $hand['value'];
                $winner = $player_id;
            } elseif ($ranking == $hand['ranking'] && $value < $hand['value']) {
                $value = $hand['value'];
                $winner = $player_id;
            } elseif ($ranking == $hand['ranking'] && $value == $hand['value']) {
                $winner = null;
            }
        }
        if (! empty($winner)) {
            $round[$winner]['winner'] = true;
        }
        return $round;
    }

    /**
     * @return RuleAbstraction
     */
    public function getRule(): RuleAbstraction
    {
        $rule = $this->rule();
        $this->rule = new $rule();

        return $this->rule;
    }

    /**
     * @param array $hand
     * @return string
     */
    protected function checkHand(array $hand): string
    {
        foreach ($this->rule->getRules() as $rule_name => $rule)
        {
            $hand = $this->orderCards($hand);
            if ($rule['suits'] == '=') {
                if (! $this->sameSuits($hand)) {
                    continue;
                }
            }
            if (! $this->checkNumber($rule, $hand)) {
                continue;
            }
            $this->ranking = $rule['ranking'];
            return $rule_name;
        }

        $this->ranking = $rule['ranking'];
        $card_number = $this->rule->getCardNumbers();
        $this->value = $card_number[substr(end($hand), 0, 1)];
        return $rule_name;
    }

    /**
     * @param array $hand
     * @return array
     */
    private function orderCards(array $hand)
    {
        $card_number = $this->rule->getCardNumbers();

        usort($hand, function ($a, $b) use ($card_number)
        {
            $a = substr($a, 0, 1);
            $b = substr($b, 0, 1);
            return $card_number[$a] <=> $card_number[$b];
        });

        return $hand;
    }

    /**
     * @param array $hand
     * @return int
     */
    private function sequenceAmount(array $hand): int
    {
        $fist = 0;
        $sequence_amount = 0;
        $biggest = 1;

        foreach ($hand as $card) {
            $number = substr($card, 0, 1);
            $card_number = $this->rule->getCardNumbers();
            $value = $card_number[$number];
            if (empty($fist)) {
                $fist = $value;
                $sequence_amount ++;
                continue;
            }
            if ($value == ($fist + 1)) {
                $fist++;
                $sequence_amount ++;
                $biggest = ($biggest < $sequence_amount) ? $sequence_amount : $biggest;
                continue;
            }
            $biggest = ($biggest < $sequence_amount) ? $sequence_amount : $biggest;
            $fist = $value;
            $sequence_amount = 1;
        }

        return $biggest;
    }

    /**
     * @param array $rule
     * @param array $hand
     * @return bool
     */
    private function checkNumber(array $rule, array $hand): bool
    {
        if ($rule['number']['type'] == '*') {
            return true;
        } elseif ($rule['number']['type'] == 'sequence') {
            if ($this->sequenceAmount($hand) == end($rule['sequence'])) {
                if ($rule['number']['start'] != "*") {
                    return $rule['number']['start'] == substr(end($hand), 0, 1);
                }
                return true;
            }
        } elseif ($rule['number']['type'] == '=') {
            $numbers = [];
            foreach ($hand as $card) {
                $numbers[] = substr($card, 0, 1);
            }
            $repetitions = array_count_values($numbers);
            if ($this->checkRepetitions($repetitions, $rule)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array $repetitions
     * @param array $rule
     * @return bool
     */
    private function checkRepetitions(array $repetitions, array $rule): bool
    {
        $card_number = $this->rule->getCardNumbers();
        $this->value = 0;
        foreach ($rule['sequence'] as $key_rule => $repetition) {
            foreach ($repetitions as $key_repetition => $num_repetition) {
                if ($repetition == $num_repetition) {
                    $this->value = $this->value < $card_number[$key_repetition] ? $card_number[$key_repetition] : $this->value;
                    unset($rule['sequence'][$key_rule]);
                    unset($repetitions[$key_repetition]);
                    break;
                }
            }
        }

        return empty($rule['sequence']);
    }

    /**
     * @param array $hand
     * @return bool
     */
    private function sameSuits(array $hand): bool
    {
        $suit = "";
        foreach ($hand as $card) {
            $current_suit = substr($card, 1, 1);
            $suit = empty($suit) ? $current_suit : $suit;

            if ($current_suit != $suit) {
                return false;
            }
        }
        $card_number = $this->rule->getCardNumbers();
        $this->value = $card_number[substr(end($hand), 0, 1)];
        return true;
    }

    /**
     * @param int $player
     * @param array $rounds
     * @return int
     */
    public function numberWins(int $player = 1, array $rounds): int
    {
        $wins = 0;
        foreach ($rounds as $round) {
            if (isset($round[$player]['winner']) && $round[$player]['winner'] == 1) {
                $wins++;
            }
        }

        return $wins;
    }

    /**
     * @return string
     */
    abstract protected function rule(): string;
}