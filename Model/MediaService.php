<?php

namespace Okto\MediaBundle\Model;

class MediaService {

    const SERIES_STATE_ALL_ACTIVE = 10;             //everything in this series will be set active;
    const SERIES_STATE_ALL_INACTIVE = 20;           //everything in this series will be set inactive;
    const SERIES_STATE_SERIES_ACTIVE_ONLY = 30;     //the series will be set active, all episodes will be set inactive;
    const SERIES_STATE_EPISODES_ACTIVE_ONLY = 40;   //the series will be set inactive, all episodes will be set active; (makes not much sense right now)

    private $oktolab_media;
    private $oktolab_media_helper;
    private $jobService;
    private $em;

    public function __construct($oktolab_media, $oktolab_media_helper, $em, $jobService)
    {
        $this->oktolab_media = $oktolab_media;
        $this->oktolab_media_helper = $oktolab_media_helper;
        $this->jobService = $jobService;
        $this->em = $em;
    }

    public function createEpisode($series_uniqID)
    {
        $episode = $this->oktolab_media->createEpisode();
        $series = $this->oktolab_media->getSeries($series_uniqID);
        $episode->setSeries($series);
        $episode->setTags($series->getTags());
        return $episode;
    }

    public function publicateSeriesTags($uniqID)
    {
        $series = $this->oktolab_media->getSeries($uniqID);
        foreach ($series->getEpisodes() as $episode) {
            foreach ($series->getTags() as $tag) {
                if (!$episode->getTags()->contains($tag)) {
                    $episode->addTag($tag);
                }
            }
            $this->oktolab_media_helper->persistEpisode($episode);
        }
    }

    public function addExtractPosterfameJob($uniqID, $position)
    {
        $this->jobService->addJob(
            "Okto\MediaBundle\Model\ExtractPosterframeJob",
            ['uniqID' => $uniqID, 'position' => $position]
        );
    }

    /**
     * @deprecated use createEpisode
     */
    public function createEpisodeForSeries($uniqID)
    {
        return $this->createEpisode($uniqID);
    }

    public function getOktolabMediaService()
    {
        return $this->oktolab_media;
    }

    public function getOktolabMediaHelperService()
    {
        return $this->oktolab_media_helper;
    }

    public function getSeries($uniqID)
    {
        return $this->oktolab_media->getSeries($uniqID);
    }

    /**
     * use this function to set series/episodes active state in bunch
     */
    public function setSeriesState($series, $state = 10)
    {
        switch ($state) {
            case $this::SERIES_STATE_ALL_ACTIVE:
                $this->setState($series, true, true);
                break;
            case $this::SERIES_STATE_ALL_INACTIVE:
                $this->setState($series, false, false);
                break;
            case $this::SERIES_STATE_SERIES_ACTIVE_ONLY:
                $this->setState($series, true, false);
                break;
            case $this::SERIES_STATE_EPISODES_ACTIVE_ONLY:
                $this->setState($series, false, true);
                break;
            default:
                break;
        }
    }

    private function setState($series, $series_active = true, $episode_active = true)
    {
        $flusher = 0;
        foreach ($series->getEpisodes() as $episode) {
            $episode->setIsActive($episode_active);
            $this->em->persist($episode);
            $flusher++;
            if ($flusher > 20) {
                $this->em->flush();
                $this->em->clear($episode);
            }
        }

        $series->setIsActive($series_active);
        $this->em->persist($series);
        $this->em->flush();
    }
}

 ?>
