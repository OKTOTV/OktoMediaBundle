<?php

namespace Okto\MediaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Oktolab\MediaBundle\Entity\Episode as BaseEpisode;

/**
 * Episode
 * @JMS\AccessType("public_method")
 * @JMS\ExclusionPolicy("all")
 * @ORM\MappedSuperclass()
 * @ORM\Entity(repositoryClass="Okto\MediaBundle\Entity\Repository\EpisodeRepository")
 */
class Episode extends BaseEpisode
{
    /**
     *
     * @ORM\OneToMany(targetEntity="Oktolab\MediaBundle\Entity\Media", mappedBy="episode")
     */
    protected $media;

    /**
    * @JMS\Expose
    * @JMS\Groups({"okto"})
    * @ORM\ManyToOne(targetEntity="Series", inversedBy="episodes", cascade={"persist"})
    */
    protected $series;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set series
     *
     * @param \Oktolab\MediaBundle\Entity\Series $series
     * @return Episode
     */
    public function setSeries($series = null)
    {
        $this->series = $series;
        return $this;
    }

    /**
     * Get series
     *
     * @return \Oktolab\MediaBundle\Entity\Series
     */
    public function getSeries()
    {
        return $this->series;
    }

    public function getSeriesName()
    {
        if ($this->series) {
            return $this->series->getName();
        }
        return "";
    }

    /**
     * Add tags
     *
     * @param \AppBundle\Entity\Tag $tags
     * @return Episode
     */
    public function addTag(\AppBundle\Entity\Tag $tags)
    {
        $this->tags[] = $tags;

        return $this;
    }

    /**
     * Remove tags
     *
     * @param \AppBundle\Entity\Tag $tags
     */
    public function removeTag(\AppBundle\Entity\Tag $tags)
    {
        $this->tags->removeElement($tags);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        return $this->tags;
    }

    public function setTags($tags)
    {
        $this->tags = $tags;
        return $this;
    }

    public function getPosterframe($fallback = false)
    {
        if (parent::getPosterframe() || !$fallback) {
            return parent::getPosterframe();
        }
        return $this->series->getPosterframe();
    }

    /**
     * Add media
     *
     * @param \Oktolab\MediaBundle\Entity\Media $media
     * @return Episode
     */
    public function addMedia($media)
    {
        $this->media[] = $media;
        $media->setEpisode($this);
        return $this;
    }

    public function setMedia($media)
    {
        $this->media = $media;
    }

    /**
     * Remove media
     *
     * @param \Oktolab\MediaBundle\Entity\Media $media
     */
    public function removeMedia($media)
    {
        $this->media->removeElement($media);
    }

    /**
     * Get media
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMedia()
    {
        return $this->media;
    }
}
