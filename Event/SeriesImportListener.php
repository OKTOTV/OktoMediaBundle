<?php

namespace Okto\MediaBundle\Event;

use Oktolab\MediaBundle\Event\ImportedSeriesMetadataEvent;
use Oktolab\MediaBundle\Event\ImportedSeriesPosterframeEvent;

class EpisodeImportListener {

    private $job_service;

    public function __construct($job_service)
    {
        $this->job_service = $job_service;
    }

    public function onSeriesMetadataImport(ImportedSeriesMetadataEvent $event)
    {
        $this->job_service->addJob(
            "Okto\MediaBundle\Model\ImportSeriesJob",
            ['uniqID' => $event->getUniqID()]
        );
    }
}
