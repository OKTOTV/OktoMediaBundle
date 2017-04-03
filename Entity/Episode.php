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
 */
class Episode extends BaseEpisode
{
    /**
    * @JMS\Expose
    * @JMS\Groups({"okto"})
    * @JMS\Type("Okto\MediaBundle\Entity\Series")
    * @ORM\ManyToOne(targetEntity="Oktolab\MediaBundle\Entity\SeriesInterface", inversedBy="episodes", cascade={"persist"})
    */
    protected $series;

    /**
     * @JMS\Expose
     * @JMS\Type("array<string>")
     * @JMS\Groups({"search", "okto"})
     * @ORM\ManyToMany(targetEntity="Okto\MediaBundle\Entity\TagInterface", inversedBy="episodes", cascade={"persist"})
     * @ORM\JoinTable(name="episode_tag")
     */
    protected $tags;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
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
        if ($series) {
            $series->addEpisode($this);
        }
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

    public function getPosterframe($fallback = false)
    {
        if (parent::getPosterframe() || !$fallback) {
            return parent::getPosterframe();
        }
        if ($this->series) {
            return $this->series->getPosterframe();
        }
        return null;
    }

    /**
     * Add tags
     *
     * @param $tag
     * @return Episode
     */
    public function addTag($tag)
    {
        $this->tags[] = $tag;
        $tag->addEpisode($this);
        return $this;
    }

    /**
     * Remove tags
     *
     * @param \AppBundle\Entity\Tag $tags
     */
    public function removeTag($tag)
    {
        $this->tags->removeElement($tag);
        return $this;
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
}
