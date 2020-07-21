<?php
namespace App\Command;

use App\Service\CreateHandsService;
use App\Service\Load\PokerHandsLoad;
use App\Service\Rule\PokerRule;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ImportHandsCommand
 * @package App\Command
 */
class ImportHandsCommand extends Command
{
    protected static $defaultName = 'hand:import';

    /**
     * @var CreateHandsService
     */
    protected $create_hands_service;

    /**
     * ImportHandsCommand constructor.
     * @param CreateHandsService $create_hands_service
     * @param PokerHandsLoad $load_hands_service
     * @param PokerRule $poker_rule
     */
    public function __construct(
        CreateHandsService $create_hands_service,
        PokerHandsLoad $load_hands_service,
        PokerRule $poker_rule
    )
    {
        $this->create_hands_service = $create_hands_service;
        $load_hands_service->setCardsHand($poker_rule->getCardsHand());
        $this->create_hands_service->importHands($load_hands_service);
        parent::__construct();
    }

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setDescription('Import all hands from data/hands.txt.')
            ->setHelp('Command will truncate all hands and import all from hands file');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Start hands importation',
            '============',
            '',
        ]);
        try {
            $output->writeln([
                'Truncate hands information',
                '============',
            ]);
            $this->create_hands_service->truncateHands();
            $this->printOkMessage($output);

            $output->writeln([
                'Loading new hands from file',
                '============',
            ]);
            $this->create_hands_service->create();
            $this->printOkMessage($output);

            $output->writeln([
                'Resume',
                '============',
                '',
            ]);
            $this->printResume($output);

            return Command::SUCCESS;
        } catch (\Exception $exception) {
            $output->writeln('Fail:');
            $output->writeln($exception->getMessage());
            return Command::FAILURE;
        }
    }

    /**
     * @param OutputInterface $output
     */
    protected function printResume(OutputInterface $output)
    {
        $players_count = $this->create_hands_service->getNumPlayers();
        $round_count = $this->create_hands_service->getNumRounds();
        $output->writeln([
            "Players [{$players_count}]",
            "Rounds [{$round_count}]"
        ]);
    }

    /**
     * @param OutputInterface $output
     */
    protected function printOkMessage(OutputInterface $output)
    {
        $output->writeln([
            'Ok',
            '',
        ]);
    }
}