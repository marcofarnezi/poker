<?php
namespace App\Service\Rule;

use App\Service\Load\LoadFileAbstract;

/**
 * Class RuleAbstraction
 * @package App\Service\Rule
 */
abstract class RuleAbstraction implements RuleInterface
{
    /**
     * @var string
     */
    protected $rule_name;

    /**
     * @var int
     */
    protected $cards_hand;

    /**
     * @var array
     */
    protected $card_numbers;

    /**
     * @var array
     */
    protected $rules;

    /**
     * @var LoadFileAbstract
     */
    protected $load_file;

    public function __construct()
    {
        $this->load_file = $this->getLoadFile();
        $this->load();
    }

    /**
     * @return void
     */
    public function load()
    {
        $rule_content = $this->load_file->toArray();

        $this->rule_name = $rule_content['rule_name'] ?? '';
        $this->cards_hand = $rule_content['cards_hand'] ?? 0;
        $this->card_numbers = $rule_content['card_numbers'] ?? [];
        $this->rules = $rule_content['rules'] ?? [];
    }

    /**
     * @return string
     */
    public function getRuleName(): string
    {
        return $this->rule_name;
    }

    /**
     * @return int
     */
    public function getCardsHand(): int
    {
        return $this->cards_hand;
    }

    /**
     * @return array
     */
    public function getCardNumbers(): array
    {
        return $this->card_numbers;
    }

    /**
     * @return array
     */
    public function getRules(): array
    {
        return $this->rules;
    }

    /**
     * @return LoadFileAbstract
     */
    public function getLoadFile(): LoadFileAbstract
    {
        $load_file_name = $this->loadFile();
        $this->load_file = new $load_file_name();
        return $this->load_file;
    }

    /**
     * @return string
     */
    abstract protected function loadFile(): string;
}