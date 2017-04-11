<?php

namespace BeerBundle\Command;

use BeerBundle\Exception\NoMapException;
use BeerBundle\Model\Location;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

/**
 * Class BeerSearchCommand.
 */
class BeerSearchCommand extends ContainerAwareCommand
{
    /**
     * Our command configuration.
     *
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function configure()
    {
        $this
            ->setName('beer:search')
            ->addOption('lat', null, InputOption::VALUE_REQUIRED, 'LAT')
            ->addOption('long', null, InputOption::VALUE_REQUIRED, 'LONG');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     *
     * @throws \Symfony\Component\Serializer\Exception\UnexpectedValueException
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Symfony\Component\Console\Exception\LogicException
     * @throws \LogicException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \BeerBundle\Exception\NoMapException
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $beerService = $this->getContainer()->get('beer.beer_service');
        if (!$beerService->isDatabaseReady()) {
            $question = new ConfirmationQuestion('Database not ready, prepare database? <Y/n>', true);
            if ($this->getHelper('question')->ask($input, $output, $question)) {
                $beerService->importDatabase();
            } else {
                throw new NoMapException();
            }
        }

        $location = new Location($input->getOption('lat'), $input->getOption('long'));

        $tripService = $this->getContainer()->get('beer.trip_service');
        $tripService->startTrip($location);
    }

}
