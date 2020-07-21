<?php
namespace App\Repository;

/**
 * Interface RepositoryInterface
 * @package App\Repository
 */
interface RepositoryInterface
{
    /**
     * @return void
     */
    public function truncate();

    /**
     * @param array $data
     */
    public function insert(array $data);
}