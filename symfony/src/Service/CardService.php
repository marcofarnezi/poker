<?php
namespace App\Service;

use App\Service\Rule\RuleAbstraction;

/**
 * Class CardService
 * @package App\Service
 */
class CardService
{
    /**
     * @var RuleAbstraction
     */
    private $rules;

    /**
     * @var array
     */
    private $highest;

    /**
     * @param RuleAbstraction $rules
     */
    public function setRules(RuleAbstraction $rules)
    {
        $this->rules = $rules;
    }

    /**
     * @param string $card
     * @return string
     */
    public function getCardNumber(string $card): string
    {
        return substr($card, 0, 1);
    }

    /**
     * @param string $card
     * @return string
     */
    public function getCardSuit(string $card): string
    {
        return substr($card, 1, 1);
    }

    /**
     * @param $card_number
     * @return int
     */
    public function getHighestCard($card_number): int
    {
        if (empty($this->highest)) {
            $this->highest = $this->rules->getCardNumbers();
        }
        return $this->highest[$card_number];
    }
}