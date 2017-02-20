<?php

namespace Okto\MediaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Oktolab\MediaBundle\Entity\Series as BaseSeries;
use Doctrine\Common\Collections\ArrayCollection;
use Okto\MediaBundle\Entity\Reachme;
use Oktolab\MediaBundle\Entity\Playlist;
use JMS\Serializer\Annotation as JMS;

/**
 * Series
 *
 * @ORM\MappedSuperclass()
 * @ ORM\Entity(repositoryClass="Okto\MediaBundle\Entity\Repository\SeriesRepository")
 */
class Series extends BaseSeries
{
    /**
    * @JMS\Expose
    * @JMS\Groups({"oktolab","okto"})
    * @JMS\Type("array<Okto\MediaBundle\Entity\Episode>")
    * @ORM\OneToMany(targetEntity="Oktolab\MediaBundle\Entity\EpisodeInterface", mappedBy="series", cascade="remove")
    * @ORM\OrderBy({"onlineStart" = "DESC"})
    */
    protected $episodes;

    /**
     * @ORM\OneToMany(targetEntity="Oktolab\MediaBundle\Entity\PlaylistInterface", mappedBy="series")
     */
    protected $playlists;

    /**
     * @ORM\OneToMany(targetEntity="Okto\MediaBundle\Entity\Reachme", mappedBy="series")
     */
    protected $reachmes;

    /**
     * default tags for episodes
     * @ORM\ManyToMany(targetEntity="Okto\MediaBundle\Entity\TagInterface", inversedBy="series", cascade={"persist"})
     * @ORM\JoinTable(name="series_tag")
     */
    protected $tags;

    public function __construct() {
        parent::__construct();
        $this->episodes = new ArrayCollection();
        $this->playlists = new ArrayCollection();
        $this->reachmes = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    /**
     * Add episodes
     *
     * @param \Oktolab\MediaBundle\Entity\Episode $episodes
     * @return Series
     */
    public function addEpisode(\Oktolab\MediaBundle\Entity\Episode $episodes)
    {
        $this->episodes[] = $episodes;
        return $this;
    }

    /**
     * Remove episodes
     *
     * @param \Oktolab\MediaBundle\Entity\Episode $episodes
     */
    public function removeEpisode(\Oktolab\MediaBundle\Entity\Episode $episodes)
    {
        $this->episodes->removeElement($episodes);
    }

    /**
     * Get episodes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEpisodes()
    {
        return $this->episodes;
    }

    public function setEpisodes($episodes)
    {
        $this->episodes = $episodes;
        return $this;
    }

    /**
     * Add playlists
     *
     * @param \MediaBundle\Entity\Playlist $playlists
     * @return Series
     */
    public function addPlaylist(Playlist $playlists)
    {
        $this->playlists[] = $playlists;

        return $this;
    }

    /**
     * Remove playlists
     *
     * @param \MediaBundle\Entity\Playlist $playlists
     */
    public function removePlaylist(Playlist $playlists)
    {
        $this->playlists->removeElement($playlists);
    }

    /**
     * Get playlists
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPlaylists()
    {
        return $this->playlists;
    }

    /**
     * Add reachmes
     *
     * @param \AppBundle\Entity\Reachme $reachmes
     * @return Series
     */
    public function addReachme(Reachme $reachmes)
    {
        $this->reachmes[] = $reachmes;

        return $this;
    }

    /**
     * Remove reachmes
     *
     * @param \AppBundle\Entity\Reachme $reachmes
     */
    public function removeReachme(Reachme $reachmes)
    {
        $this->reachmes->removeElement($reachmes);
    }

    /**
     * Get reachmes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReachmes()
    {
        return $this->reachmes;
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function setTags($tags)
    {
        $this->tags = $tags;
        return $this;
    }

    public function addTag($tag)
    {
        $this->tags[] = $tag;
        return $this;
    }

    public function removeTag($tag)
    {
        $this->tags->removeElement($tag);
        return $this;
    }
}
