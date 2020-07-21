<?php
namespace App\Service\Rule;

use App\Service\Load\LoadFileAbstract;

/**
 * Interface RuleInterface
 * @package App\Service\Rule
 */
interface RuleInterface
{
    /**
     * @return void
     */
    public function load();

    /**
     * @return string
     */
    public function getRuleName(): string;

    /**
     * @return int
     */
    public function getCardsHand(): int;

    /**
     * @return array
     */
    public function getCardNumbers(): array;

    /**
     * @return array
     */
    public function getRules(): array;

    /**
     * @return LoadFileAbstract
     */
    public function getLoadFile(): LoadFileAbstract;
}