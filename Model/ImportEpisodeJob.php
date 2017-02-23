<?php

namespace Okto\MediaBundle\Model;

use Bprs\CommandLineBundle\Model\BprsContainerAwareJob;
use Oktolab\MediaBundle\Model\MediaService;

class ImportEpisodeJob extends BprsContainerAwareJob {

    private $media_service;
    private $logbook;
    private $serializer;

    public function perform()
    {
        $this->logbook = $this->getContainer()->get('bprs_logbook');
        $this->logbook->info('okto_media.start_episode_import', [], $this->args['uniqID']);
        $this->media_service = $this->getContainer()->get('oktolab_media');
        $episode = $this->media_service->getEpisode($this->args['uniqID']);

        $serializing_schema = $this->getContainer()->getParameter('oktolab_media.serializing_schema');
        if ($serializing_schema) {
            $response = $this->media_service->getResponse($episode->getKeychain(), MediaService::ROUTE_EPISODE, ['uniqID' => $this->args['uniqID'], 'group' => $serializing_schema]);
        } else {
            $response = $this->media_service->getResponse($episode->getKeychain(), MediaService::ROUTE_EPISODE, ['uniqID' => $this->args['uniqID']]);
        }
        if ($response->getStatusCode() == 200) {
            $this->serializer = $this->getContainer()->get('jms_serializer');
            $episode_class = $this->getContainer()->getParameter('oktolab_media.episode_class');
            $remote_episode = $this->serializer->deserialize($response->getBody(), $episode_class, 'json');
            $series = $this->media_service->getSeries($remote_episode->getSeries()->getUniqID());
            if (!$series) {
                $series = $this->importSeries($remote_episode->getSeries()->getUniqID(), $episode->getKeychain());
            }
            $episode->setSeries($series);
            $this->importTags($episode, $remote_episode);

            $em = $this->getContainer()->get('doctrine.orm.entity_manager');
            $em->persist($episode);
            $em->persist($series);
            $em->flush();
        } else {
            $this->media_service->setEpisodeStatus($this->args['uniqID'], Episode::STATE_NOT_READY);
            $this->logbook->error('okto_media.episode_import_episode_error', [], $this->args['uniqID']);
        }
        $this->logbook->info('okto_media.end_episode_import', [], $this->args['uniqID']);
    }

    public function getName()
    {
        return 'Import Episode';
    }

    private function importSeries($uniqID, $keychain)
    {
        $response = $this->media_service->getResponse(
            $keychain,
            MediaService::ROUTE_SERIES,
            ['uniqID' => $uniqID]
        );
        if ($response->getStatusCode() == 200) {
            $series_class = $this->getContainer()->getParameter('oktolab_media.series_class');
            $series = $this->serializer->deserialize($response->getBody(), $series_class, 'json');
            $local_series = $this->media_service->getSeries($uniqID);
            if (!$local_series) {
                $local_series = $this->media_service->createSeries();
            }
            $local_series->merge($series);

            //import Series Posterframe
            $this->media_service->addImportSeriesPosterframeJob($local_series->getUniqID(), $keychain, $series->getPosterframe());
            return $local_series;
        } else {
            $this->logbook->error('okto_media.episode_import_series_error', [], $this->args['uniqID']);
        }
        return null;
    }

    private function importTags($episode, $remote_episode)
    {
        $tag_service = $this->getContainer()->get('okto_media_tag');
        foreach ($remote_episode->getTags() as $tag) {
            $localtag = $tag_service->getTagByText($tag);
            if (!$localtag) {
                $localtag = $tag_service->createTag();
                $localtag->setText($tag);
            }
            if (!$episode->getTags()->contains($localtag)) {
                $episode->addTag($localtag);
            }
        }
    }
}
