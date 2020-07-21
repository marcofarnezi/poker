<?php
namespace App\Repository;

use App\Entity\Hand;

/**
 * Class HandRepository
 * @package App\Repository
 */
class HandRepository extends RepositoryAbstract
{
    /**
     * @return string
     */
    protected function entity(): string
    {
        return Hand::class;
    }
}