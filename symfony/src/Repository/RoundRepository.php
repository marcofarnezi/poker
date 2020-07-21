<?php
namespace App\Repository;

use App\Entity\Round;

/**
 * Class RoundRepository
 * @package App\Repository
 */
class RoundRepository extends RepositoryAbstract
{
    /**
     * @return string
     */
    protected function entity(): string
    {
        return Round::class;
    }
}