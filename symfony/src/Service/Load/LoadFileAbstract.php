<?php
namespace App\Service\Load;

/**
 * Class LoadFileAbstract
 * @package App\Service\Load
 */
abstract class LoadFileAbstract implements LoadFileInterface
{
    /**
     * @var string
     */
    protected $file_content;

    /**
     * LoadFileAbstract constructor.
     */
    public function __construct()
    {
        $this->loadFile();
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path();
    }

    /**
     * @return string
     */
    public function loadFile(): string
    {
        if (empty($this->file_content)) {
            $this->file_content = file_get_contents($this->getPath());
        }
        return $this->file_content;
    }

    /**
     * @return string
     */
    abstract protected function path(): string;

    /**
     * @return array
     */
    abstract public function toArray(): array;
}