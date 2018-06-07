<?php
namespace Okto\MediaBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Okto\MediaBundle\Entity\Tag;

class TagRepository extends EntityRepository
{
    public function findTagBySlug($slug, $query_only = false, $tag_class = "OktoMediaBundle:Tag")
    {
        $query = $this->getEntityManager()
            ->createQuery("SELECT t FROM ".$tag_class." t WHERE t.slug = :slug")
            ->setParameter('slug', $slug);

        if ($query_only) {
            return $query;
        }

        return $query->getOneOrNullResult();
    }

    public function findEpisodesWithTag(Tag $tag, $number = 5, $query_only = false, $episode_class = "OktoMediaBundle:Episode") {
        $query = $this->getEntityManager()
            ->createQuery(
                'SELECT e FROM '.$episode_class.' e
                JOIN e.tags t
                WHERE t.id = :tag_id
                ORDER BY e.firstranAt'
            )
            ->setParameter('tag_id', $tag->getId());

        if ($query_only) {
            return $query;
        }
        return $query->setMaxResults($number)->getResult();
    }

    public function findSeriesWithTag(Tag $tag, $number = 5, $query_only = false, $series_class = "OktoMediaBundle:Series") {
        $query = $this->getEntityManager()
            ->createQuery(
                'SELECT s FROM '.$series_class.' s
                LEFT JOIN s.episodes e
                LEFT JOIN e.tags t
                WHERE t.id = :tag_id'
            )
            ->setParameter('tag_id', $tag->getId());

        if ($query_only) {
            return $query;
        }
        return $query->setMaxResults($number)->getResult();
    }

    public function findPlaylistsWithTag(Tag $tag, $number = 5, $query_only = false) {
        $query = $this->getEntityManager()
            ->createQuery(
                'SELECT p FROM OktolabMediaBundle:Playlist p
                JOIN p.items i
                JOIN i.episode e
                JOIN e.tags t
                JOIN e.posterframe pf
                WHERE t.id = :tag_id'
            )
            ->setParameter('tag_id', $tag->getId());

        if ($query_only) {
            return $query;
        }
        return $query->setMaxResults($number)->getResult();
    }

    public function findHighlightedTags($number = 6, $query_only = false)
    {
        $query = $this->getEntityManager()->createQuery(
            'SELECT t FROM OktoMediaBundle:Tag t
            WHERE t.highlight = 1
            ORDER BY t.rank ASC'
        );

        if ($query_only) {
            return $query;
        }

        return $query->setMaxResults($number)->getResult();
    }
}
