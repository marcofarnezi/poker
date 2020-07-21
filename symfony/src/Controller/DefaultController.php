<?php
namespace App\Controller;

use App\Repository\HandRepository;
use App\Service\CheckHandsService;
use App\Service\CheckOut\PokerCheckOut;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DefaultController
 * @package App\Controller
 */
class DefaultController
{
    /**
     * @param int $player
     * @param PokerCheckOut $checkHandsService
     * @param HandRepository $handRepository
     * @return Response
     */
    public function index(
        int $player = 1,
        PokerCheckOut $checkHandsService,
        HandRepository $handRepository
    ): Response
    {
        $checkHandsService->loadHands($handRepository->all());
        $rounds = [];
        foreach($checkHandsService->checkHands() as $round) {
            $rounds[] = $checkHandsService->setChampion($round);
        }
        $victories = $checkHandsService->numberWins($player, $rounds);
        return new Response(
            json_encode(["player" => $player, "victories" => $victories])
        );
    }
}