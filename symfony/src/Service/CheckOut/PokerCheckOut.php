<?php
namespace App\Service\CheckOut;

use App\Service\Rule\PokerRule;

/**
 * Class PokerCheckOut
 * @package App\Service\CheckOut
 */
class PokerCheckOut extends CheckOutAbstract
{
    /**
     * @return string
     */
    protected function rule(): string
    {
        return PokerRule::class;
    }
}