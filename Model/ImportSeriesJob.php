<?php

namespace Okto\MediaBundle\Model;

use Bprs\CommandLineBundle\Model\BprsContainerAwareJob;

class ImportSeriesJob extends BprsContainerAwareJob {

    private $media_service;
    private $jms_serializer;
    private $job_service;
    private $logbook;
    private $keychain;

    public function perform()
    {
        $this->media_service = $this->getContainer()->get('oktolab_media');
        $this->jms_serializer = $this->getContainer()->get('jms_serializer');
        $this->logbook = $this->getContainer()->get('bprs_logbook');
        $this->job_service = $this->getContainer()->get('bprs_jobservice');
        $this->keychain = $this->getContainer()->get('bprs_applink')->getKeychain($this->args['keychain']);

        $this->logbook->info('okto_media.series_import_start', [], $this->args['uniqID']);
        $series = $this->media_service->getSeries($this->args['uniqID']);
        $serializing_schema = $this->getContainer()->getParameter('oktolab_media.serializing_schema');

        $remote_series = null;
        $series_class = $this->getContainer()->getParameter('oktolab_media.series_class');
        if ($serializing_schema) {
            $response = $this->media_service->getResponse($this->keychain, MediaService::ROUTE_SERIES, ['uniqID' => $this->args['uniqID'], 'group' => $serializing_schema]);
            $remote_series = $this->jms_serializer->deserialize($response->getBody(), $series_class, 'json');
            $series->merge($remote_series);
            $em = $this->getContainer()->get('doctrine.orm.entity_manager');
            $em->persist($series);
            $em->flush();
            $this->importSeriesEpisodes($remote_series);
        } else {
            $response = $this->media_service->getResponse($this->keychain, MediaService::ROUTE_SERIES, ['uniqID' => $this->args['uniqID']]);
            $remote_series = $this->jms_serializer->deserialize($response->getBody(), $series_class, 'json');
        }

        // TODO: resort command order. this doesn't make any sense.
        if ($response->getStatusCode() == 200) {
            $this->logbook->info('okto_media.series_import_end', [], $this->args['uniqID']);
        } else {
            $this->logbook->error('okto_media.series_import_remote_error', [], $this->args['uniqID']);
        }
    }

    public function getName()
    {
        return 'Okto Series Importer';
    }

    public function importSeriesEpisodes($remote_series)
    {
        foreach ($remote_series->getEpisodes() as $episode) {
            if ($episode->getUniqID()) {
                $this->media_service->addEpisodeJob($this->keychain, $episode->getUniqID());
            }
        }
    }
}
