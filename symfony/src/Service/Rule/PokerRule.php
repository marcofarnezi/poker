<?php
namespace App\Service\Rule;

use App\Service\Load\PokerRuleLoad;

/**
 * Class PokerRule
 * @package App\Service\Rule
 */
class PokerRule extends RuleAbstraction
{
    /**
     * @return string
     */
    protected function loadFile(): string
    {
        return PokerRuleLoad::class;
    }
}