<?php
namespace App\Service\Load;

/**
 * Interface LoadFileInterface
 * @package App\Service\Load
 */
interface LoadFileInterface
{
    public function getPath(): string;
    public function loadFile(): string;
}