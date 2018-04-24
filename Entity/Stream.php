<?php

namespace Okto\MediaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Oktolab\MediaBundle\Entity\Stream as BaseStream;

/**
 * Stream
 * @JMS\AccessType("public_method")
 * @JMS\ExclusionPolicy("all")
 * @ORM\Entity(repositoryClass="Oktolab\MediaBundle\Entity\Repository\BaseStreamRepository")
 * @ORM\MappedSuperclass()
 */
class Stream extends BaseStream
{
    /**
    * @JMS\Expose
    * @JMS\Groups({"okto"})
    * @JMS\Type("Okto\MediaBundle\Entity\Series")
    * @ORM\ManyToOne(targetEntity="Oktolab\MediaBundle\Entity\SeriesInterface", inversedBy="streams", cascade={"persist"})
    */
    protected $series;

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
     * @return Stream
     */
    public function setSeries($series = null)
    {
        $this->series = $series;
        if ($series) {
            $series->addStream($this);
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
}
