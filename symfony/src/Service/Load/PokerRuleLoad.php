<?php
namespace App\Service\Load;

/**
 * Class PokerRuleLoad
 * @package App\Service\Load
 */
class PokerRuleLoad extends LoadFileAbstract
{
    /**
     * @return string
     */
    protected function path(): string
    {
        return realpath(__DIR__ . '/../Rule/data/poker.json');
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return json_decode($this->file_content, true);
    }
}