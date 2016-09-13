<?php

namespace Okto\MediaBundle\Event;

use Oktolab\MediaBundle\Event\ImportedEpisodeMetadataEvent;
use Oktolab\MediaBundle\Event\ImportedEpisodePosterframeEvent;

class EpisodeImportListener {

    private $job_service;

    public function __construct($job_service)
    {
        $this->job_service = $job_service;
    }

    public function onEpisodeMetadataImport(ImportedEpisodeMetadataEvent $event)
    {
        $this->job_service->addJob(
            "Okto\MediaBundle\Model\ImportEpisodeJob",
            ['uniqID' => $event->getUniqID()]
        );
    }
}
