<?php

namespace Okto\MediaBundle\Model;

class ImportSeriesJob {

    private $media_service;
    private $jms_serializer;
    private $job_service;
    private $logbook;

    public function perform()
    {
        $this->media_service = $this->getContainer()->get('oktolab_media');
        $this->jms_serializer = $this->getContainer()->get('jms_serializer');
        $this->logbook = $this->getContainer()->get('bprs_logbook');
        $this->job_service = $this->getContainer()->get('bprs_jobservice');

        $this->logbook->info('okto_media.series_import_start', [], $this->args['uniqID']);
        $series = $this->media_service->getSeries($this->args['uniqID']);
        $serializing_schema = $this->getContainer()->getParameter('oktolab_media.serializing_schema');

        $remote_series = null;
        $series_class = $this->getContainer()->getParameter('oktolab_media.series_class');
        if ($serializing_schema) {
            $response = $this->mediaService->getResponse($this->keychain, MediaService::ROUTE_EPISODE, ['uniqID' => $this->args['uniqID'], 'group' => $serializing_schema]);
            $remote_series = $this->serializer->deserialize($response->getBody(), $series_class, 'json');
            $series->merge($remote_series);
            $em = $this->getContainer()->get('doctrine.orm.entity_manager');
            $em->persist($series);
            $em->flush();
        } else {
            $response = $this->mediaService->getResponse($this->keychain, MediaService::ROUTE_EPISODE, ['uniqID' => $this->args['uniqID']]);
            $remote_series = $this->serializer->deserialize($response->getBody(), $series_class, 'json');
        }
        if ($response->getStatusCode() == 200) {
            $keychain = $this->getContainer()->get('bprs_applink')->getKeychain($this->args['keychain']);
            foreach ($remote_series->getEpisodes() as $episode) {
                $this->media_service->addEpisodeJob($keychain, $episode->getUniqID());
            }
            $this->logbook->info('okto_media.series_import_end', [], $this->args['uniqID']);
        } else {
            $this->logbook->error('okto_media.series_import_remote_error', [], $this->args['uniqID']);
        }
    }

    public function getName()
    {
        return 'Okto Series Importer';
    }
}
