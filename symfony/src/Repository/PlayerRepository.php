<?php
namespace App\Repository;

use App\Entity\Player;

/**
 * Class PlayerRepository
 * @package App\Repository
 */
class PlayerRepository extends RepositoryAbstract
{
    /**
     * @return string
     */
    protected function entity(): string
    {
        return Player::class;
    }
}