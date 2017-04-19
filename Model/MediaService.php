<?php

namespace Okto\MediaBundle\Model;

class MediaService {

    const SERIES_STATE_ALL_ACTIVE = 10;             //everything in this series will be set active;
    const SERIES_STATE_ALL_INACTIVE = 20;           //everything in this series will be set inactive;
    const SERIES_STATE_SERIES_ACTIVE_ONLY = 30;     //the series will be set active, all episodes will be set inactive;
    const SERIES_STATE_EPISODES_ACTIVE_ONLY = 40;   //the series will be set inactive, all episodes will be set active; (makes not much sense right now)

    private $oktolab_media;
    private $oktolab_media_helper;
    private $em;

    public function __construct($oktolab_media, $oktolab_media_helper, $em)
    {
        $this->oktolab_media = $oktolab_media;
        $this->oktolab_media_helper = $oktolab_media_helper;
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

    public function createEpisodeForSeries($uniqID)
    {
        $series = $this->oktolab_media->getSeries($uniqID);
        $episode = $this->oktolab_media->createEpisode();
        $episode->setSeries($series);
        $episode->setTags($series->getTags());

        return $episode;
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
                // $repo = $this->getOktolabMediaService()->getEpisodeRepo();
                // $episodes = $repo->findEpisodesForSeries($series->getUniqID(), true, $this->episode_class)->iterate();
                $flusher = 0;
                // while (($row = $episodes->next()) !== false) {
                //     die(var_dump($row));
                // }
                foreach ($series->getEpisodes() as $episode) {
                    $episode->setIsActive(true);
                    $this->em->persist($episode);
                    $flusher++;
                    if ($flusher > 20) {
                        $this->em->flush();
                        $this->em->clear();
                    }
                }

                $series->setIsActive(true);
                $this->em->persist($series);
                $this->em->flush();
                break;
            case $this::SERIES_STATE_ALL_INACTIVE:
                $flusher = 0;
                foreach ($series->getEpisodes() as $episode) {
                    $episode->setIsActive(false);
                    $this->em->persist($episode);
                    $flusher++;
                    if ($flusher > 20) {
                        $this->em->flush();
                        $this->em->clear();
                    }
                }

                $series->setIsActive(false);
                $this->em->persist($series);
                $this->em->flush();
                break;
            case $this::SERIES_STATE_SERIES_ACTIVE_ONLY:
                $flusher = 0;
                foreach ($series->getEpisodes() as $episode) {
                    $episode->setIsActive(false);
                    $this->em->persist($episode);
                    $flusher++;
                    if ($flusher > 20) {
                        $this->em->flush();
                        $this->em->clear();
                    }
                }

                $series->setIsActive(true);
                $this->em->persist($series);
                $this->em->flush();
                break;
            case $this::SERIES_STATE_EPISODES_ACTIVE_ONLY:
                $flusher = 0;
                foreach ($series->getEpisodes() as $episode) {
                    $episode->setIsActive(true);
                    $this->em->persist($episode);
                    $flusher++;
                    if ($flusher > 20) {
                        $this->em->flush();
                        $this->em->clear();
                    }
                }

                $series->setIsActive(false);
                $this->em->persist($series);
                $this->em->flush();
                break;
            default:
                # code...
                break;
        }
    }
}

 ?>
