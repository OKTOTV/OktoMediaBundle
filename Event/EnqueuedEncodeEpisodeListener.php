<?php

namespace Okto\MediaBundle\Event;

use Oktolab\MediaBundle\Event\ImportedEpisodeMetadataEvent;
use Oktolab\MediaBundle\Event\ImportedEpisodePosterframeEvent;

class EnqueuedEncodeEpisodeListener {

    private $logbook;
    private $security_token_storage;

    public function __construct($logbook, $security_token_storage)
    {
        $this->logbook = $logbook;
        $this->security_token_storage = $security_token_storage;
    }

    public function onEpisodeMetadataImport(ImportedEpisodeMetadataEvent $event)
    {
        $username = "system";
        if ($security_token_storage->getToken()) {
            $username = $this->security_token_storage->getToken()->getUser()->getUsername();
        }

        $this->logbook->info(
            "okto_media.enqueued_encode_episode",
            ["%user%" => $username],
            $event->getArgs()['uniqID']
        );
    }
}
