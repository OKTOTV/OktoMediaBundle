<?php
namespace Okto\MediaBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Okto\MediaBundle\Entity\Tag;

class TagRepository extends EntityRepository
{
    public function findEpisodesWithTag(Tag $tag, $number = 5, $query_only = false) {
        $query = $this->getEntityManager()
            ->createQuery('SELECT e FROM OktoMediaBundle:Episode e JOIN e.tags t WHERE t.id = :tag_id ORDER BY e.firstranAt ')
            ->setParameter('tag_id', $tag->getId());

        if ($query_only) {
            return $query;
        }
        return $query->setMaxResults($number)->getResult();
    }

    public function findSeriesWithTag(Tag $tag, $number = 5, $query_only = false) {
        $query = $this->getEntityManager()
            ->createQuery('SELECT s FROM MediaBundle:Series s LEFT JOIN s.episodes e LEFT JOIN e.tags t WHERE t.id = :tag_id')
            ->setParameter('tag_id', $tag->getId());

        if ($query_only) {
            return $query;
        }
        return $query->setMaxResults($number)->getResult();
    }

    public function findPlaylistsWithTag(Tag $tag, $number = 5, $query_only = false) {
        $query = $this->getEntityManager()
            ->createQuery('SELECT p FROM MediaBundle:Playlist p JOIN p.items i JOIN i.episode e JOIN e.tags t WHERE t.id = :tag_id')
            ->setParameter('tag_id', $tag->getId());

        if ($query_only) {
            return $query;
        }
        return $query->setMaxResults($number)->getResult();
    }

    public function findHighlightedTags($number = 6, $query_only = false)
    {
        $query = $this->getEntityManager()
            ->createQuery('SELECT t FROM OktoMediaBundle:Tag t WHERE t.highlight = 1 ORDER BY t.rank ASC');

        if ($query_only) {
            return $query;
        }
        return $query->setMaxResults($number)->getResult();
    }
}
