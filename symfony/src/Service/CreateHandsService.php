<?php
namespace App\Service;

use App\Repository\HandRepository;
use App\Repository\PlayerRepository;
use App\Repository\RoundRepository;
use App\Service\Load\LoadFileAbstract;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

/**
 * Class CreateHandsService
 * @package App\Service
 */
class CreateHandsService
{
    const PLAYER_NAME = 'player_';
    const ROUND_NAME = 'round_';

    /**
     * @var PlayerRepository
     */
    private $player_repository;

    /**
     * @var RoundRepository
     */
    private $round_repository;

    /**
     * @var HandRepository
     */
    private $hand_repository;

    /**
     * @var array
     */
    private $hands;

    /**
     * @var int
     */
    private $amount_rounds = 0;

    /**
     * @var int
     */
    private $amount_players = 0;

    /**
     * CreateHandsService constructor.
     * @param PlayerRepository $player_repository
     * @param RoundRepository $round_repository
     * @param HandRepository $hand_repository
     */
    public function __construct(
        PlayerRepository $player_repository,
        RoundRepository $round_repository,
        HandRepository $hand_repository
    )
    {
        $this->player_repository = $player_repository;
        $this->round_repository = $round_repository;
        $this->hand_repository = $hand_repository;
    }

    /**
     * @return void
     * @throws DBALException
     */
    public function truncateHands()
    {
        $this->hand_repository->truncate();
        $this->player_repository->truncate();
        $this->round_repository->truncate();
    }

    /**
     * @param LoadFileAbstract $load_hands_service
     * @return int
     */
    public function importHands(LoadFileAbstract $load_hands_service): int
    {
        $this->hands = $load_hands_service->toArray();
        return count($this->hands);
    }

    /**
     * @return void
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function create(): void
    {
        $player_entity_list = [];
        foreach ($this->hands as $number_round => $players) {
            $round_entity = $this->round_repository->insert([
                'name' => self::ROUND_NAME . $number_round
            ]);
            $this->amount_rounds ++;
            foreach ($players as $number_player => $hands) {
                $player_name = self::PLAYER_NAME . $number_player;
                if (! array_key_exists($player_name, $player_entity_list)) {
                    $player_entity = $this->player_repository->insert([
                        'name' => $player_name
                    ]);
                    $player_entity_list[$player_name] = $player_entity;
                    $this->amount_players ++;
                }
                $this->hand_repository->insert([
                    'round' => $round_entity,
                    'player' => $player_entity_list[$player_name],
                    'cards' => json_encode($hands)
                ]);
            }
        }
    }

    /**
     * @return int
     */
    public function getNumRounds(): int
    {
        return $this->amount_rounds;
    }

    /**
     * @return int
     */
    public function getNumPlayers(): int
    {
        return $this->amount_players;
    }
}