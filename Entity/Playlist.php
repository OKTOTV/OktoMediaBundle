<?php

namespace Okto\MediaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Oktolab\MediaBundle\Entity\Playlist as BasePlaylist;

/**
 * Playlist
 * @JMS\AccessType("public_method")
 * @JMS\ExclusionPolicy("all")
 * @ORM\MappedSuperclass()
 */
class Playlist extends BasePlaylist
{
    /**
    * @JMS\Expose
    * @JMS\Groups({"okto"})
    * @JMS\Type("Okto\MediaBundle\Entity\Series")
    * @ORM\ManyToOne(targetEntity="Oktolab\MediaBundle\Entity\SeriesInterface", inversedBy="playlists", cascade={"persist"})
    */
    protected $series;

    public function getSeries()
    {
        return $this->series;
    }

    public function setSeries($series = null)
    {
        $this->series = $series;
        return $this;
    }
}
