<?php

namespace Okto\MediaBundle\Model;

class MediaService {

    private $oktolab_media;
    private $oktolab_media_helper;

    public function __construct($oktolab_media, $oktolab_media_helper)
    {
        $this->oktolab_media = $oktolab_media;
        $this->oktolab_media_helper = $oktolab_media_helper;
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
}

 ?>
