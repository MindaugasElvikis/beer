<?php

namespace BeerBundle\Command;

use BeerBundle\Exception\NoMapException;
use BeerBundle\Model\LocationModel;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Stopwatch\Stopwatch;

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
        $stopwatch = new Stopwatch();
        $stopwatch->start('trip');

        $beerService = $this->getContainer()->get('beer.beer_service');
        $tripService = $this->getContainer()->get('beer.trip_service');
        $distanceService = $this->getContainer()->get('beer.distance_service');

        if (!$beerService->isDatabaseReady()) {
            $question = new ConfirmationQuestion('Database not ready, prepare database? <Y/n>', true);
            if ($this->getHelper('question')->ask($input, $output, $question)) {
                $beerService->importDatabase();
            } else {
                throw new NoMapException();
            }
        }

        $myLocation = new LocationModel($input->getOption('lat'), $input->getOption('long'));


        $locations = $tripService->travel($myLocation);
        $beers = $tripService->getBeersFromPoints($locations);

        $output->writeln(strtr('Found %count% beer factories:', ['%count%' => count($locations)]));
        $output->writeln(strtr('-> HOME: %lat%, %long% distance %distance%km', [
            '%lat%'      => $myLocation->getLatitude(),
            '%long%'     => $myLocation->getLongitude(),
            '%distance%' => 0,
        ]));
        foreach ($locations as $key => $location) {
            if ($key === 0) {
                $distance = $distanceService->getDistance($myLocation, $location);
            } else {
                $distance = $distanceService->getDistance($locations[$key - 1], $location);
            }

            $output->writeln(strtr('-> [%id%] %title%: %lat%, %long% distance %distance%km', [
                '%id%'       => $location->getBrewery()->getId(),
                '%title%'    => $location->getBrewery()->getName(),
                '%lat%'      => $location->getLatitude(),
                '%long%'     => $location->getLongitude(),
                '%distance%' => number_format($distance),
            ]));
        }
        $output->writeln(strtr('-> HOME: %lat%, %long% distance %distance%km', [
            '%lat%'      => $myLocation->getLatitude(),
            '%long%'     => $myLocation->getLongitude(),
            '%distance%' => number_format($distanceService->getDistance($locations[count($locations) - 1],
                $myLocation)),
        ]));

        $output->writeln(strtr(
            'Total distance travelled: %distance%km',
            ['%distance%' => number_format($tripService->sumPointsDistance($locations), 0, '.', '')]
        ));

        $output->writeln(strtr('Collected %count% beer types:', ['%count%' => count($beers)]));
        foreach ($beers as $beer) {
            $output->writeln(strtr('-> %beer%', ['%beer%' => $beer]));
        }
        $event = $stopwatch->stop('trip');

        $output->writeln(strtr('Program took: %duration%ms', ['%duration%' => $event->getDuration()]));
    }
}
