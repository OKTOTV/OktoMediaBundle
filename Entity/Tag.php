<?php

namespace Okto\MediaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Tag
 * @JMS\AccessType("public_method")
 * @JMS\ExclusionPolicy("all")
 * @UniqueEntity("text")
 * @ORM\Table()
 * @ORM\HasLifecycleCallbacks()
 * @ORM\MappedSuperclass()
 */
class Tag
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @JMS\Expose
     * @JMS\Type("string")
     * @JMS\Groups({"okto"})
     * @Assert\Length(min = 3, max = 30, maxMessage = "okto_media.max_tag_limit", minMessage = "okto_media.min_tag_limit")
     * @ORM\Column(name="text", type="string", length=70, unique=true)
     */
    private $text;

    /**
     * @JMS\Expose
     * @JMS\Type("string")
     * @JMS\Groups({"okto"})
     * @ORM\Column(length=72, unique=true)
     * @Gedmo\Slug(fields={"text"}, updatable=false, separator="_")
     */
    private $slug;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * @JMS\Type("array<Okto\MediaBundle\Entity\Episode>")
     * @JMS\Groups({"okto"})
     * @ORM\ManyToMany(targetEntity="Oktolab\MediaBundle\Entity\EpisodeInterface", mappedBy="tags")
     */
    protected $episodes;

    /**
     * @ORM\ManyToMany(targetEntity="Oktolab\MediaBundle\Entity\SeriesInterface", mappedBy="tags")
     */
    protected $series;

    public function __construct()
    {
        $this->updatedAt = new \Datetime();
    }

    public function __toString()
    {
        return $this->text;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return Tag
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set updatedAt
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     *
     * @param \DateTime $updatedAt
     * @return Tag
     */
    public function setUpdatedAt()
    {
        $this->updatedAt = new \DateTime();
        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Tag
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    public function getEpisodes()
    {
        return $this->episodes;
    }

    public function addEpisode($episode)
    {
        $this->episodes[] = $episode;
        return $this;
    }

    public function removeEpisode($episode)
    {
        $this->episodes->removeElement($episode);
        return $this;
    }

    public function setEpisodes($episodes)
    {
        $this->episodes = $episodes;
        return $this;
    }

    public function getSeries()
    {
        return $this->series;
    }

    public function addSeries($series)
    {
        $this->series[] = $series;
        return $this;
    }

    public function removeSeries($series)
    {
        $this->series->removeElement($series);
        return $this;
    }

    public function setSeries($seriess)
    {
        $this->series = $seriess;
        return $this;
    }
}
