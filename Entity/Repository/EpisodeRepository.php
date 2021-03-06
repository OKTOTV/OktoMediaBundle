<?php

namespace Okto\MediaBundle\Entity\Repository;

use Oktolab\MediaBundle\Entity\Repository\BaseEpisodeRepository;

class EpisodeRepository extends BaseEpisodeRepository
{
    public function findAllForClass($episode_class, $query_only = false)
    {
        $query = $this->getEntityManager()->createQuery(
                sprintf(
                    "SELECT e, p, s
                    FROM %s e
                    LEFT JOIN e.posterframe p
                    LEFT JOIN e.series s
                    ORDER BY e.updatedAt DESC", $episode_class)
            );

        if ($query_only) {
            return $query;
        }
        return $query->getResult();
    }

    public function findInactiveEpisodesAction($episode_class, $query_only = false)
    {
        $query = $this->getEntityManager()->createQuery(
            sprintf(
                "SELECT e, s FROM %s e
                LEFT JOIN e.series s
                WHERE e.isActive = 0 AND s.isActive = 1
                ORDER BY s.name ASC",
                $episode_class
                )
            );

        if ($query_only) {
            return $query;
        }

        return $query->getResult();
    }
}

?>
