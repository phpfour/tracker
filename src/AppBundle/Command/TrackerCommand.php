<?php

namespace AppBundle\Command;

use GuzzleHttp\Client;
use AppBundle\Entity\Timelog;
use AppBundle\Service\WakaTime;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class TrackerCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('app:tracker')
            ->setDescription('Tracks time from WakaTime.')
            ->addArgument('date', InputArgument::OPTIONAL, 'Date for which to get data', date('Y-m-d'));
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em       = $this->getContainer()->get('doctrine.orm.default_entity_manager');
        $wakatime = new WakaTime(new Client(), $this->getContainer()->getParameter('api_key'));

        $durations = $wakatime->durations($input->getArgument('date'));

        foreach ($durations['data'] as $row) {

            $timelog = new Timelog();
            $timelog->setProject($row['project']);
            $timelog->setDuration($row['duration']);
            $timelog->setTime(new \DateTime(date('Y-m-d H:i:s', $row['time'])));

            $output->writeln("Recording " . round($row['duration'] / 60, 2) . " minutes of time for " . $row['project'] . " project.");

            $em->persist($timelog);
        }

        $em->flush();
    }
}
