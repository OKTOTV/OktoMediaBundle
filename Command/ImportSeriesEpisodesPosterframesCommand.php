<?php

namespace Okto\MediaBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;

class ImportSeriesEpisodesPosterframesCommand extends ContainerAwareCommand {

    public function __construct() {
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('okto:media:import_series_episodes_posterframes')
            ->setDescription('Imports all posterframes for all episodes for a given series. Overwrites existing poserframes')
            ->addArgument('uniqID', InputArgument::REQUIRED, 'the uniqID of your series')
            ->addOption('queue', null, InputOption::VALUE_REQUIRED, 'the queue you want to add this job to');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $media_service = $this->getContainer()->get('oktolab_media');
        $series = $media_service->getSeries($input->getArgument('uniqID'));

        if ($series) {
            $output->writeln('series found! adding jobs...');
            foreach ($series->getEpisodes() as $episode) {
                $output->write('.');
                $this->getContainer()->get('oktolab_media')->addEpisodePosterframeJob(
                    $episode->getUniqID(),
                    $input->getOption('queue')
                );
            }
        } else {
            $output->writeln('no series found!');
        }
    }
}
