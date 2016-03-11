<?php

namespace AppBundle\Command;

use AppBundle\Entity\Timelog;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class StatCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('app:stat')
            ->setDescription('Show statistics of timelog.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $repo = $this->getContainer()->get('doctrine.orm.default_entity_manager')->getRepository('AppBundle:Timelog');
        $stat = $repo->getProjectStat();

        foreach ($stat as $key => $value) {
            $stat[$key]['total'] = $this->secondsToTime(intval($stat[$key]['total']));
        }

        $table = new Table($output);
        $table
            ->setHeaders(['Project', 'Duration'])
            ->setRows($stat);

        $table->render();
    }

    private function secondsToTime($seconds)
    {
        $dtF = new \DateTime("@0");
        $dtT = new \DateTime("@$seconds");

        return $dtF->diff($dtT)->format('%h hours, %i minutes and %s seconds');
    }
}
