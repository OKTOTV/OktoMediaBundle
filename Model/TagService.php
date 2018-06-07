<?php

namespace Okto\MediaBundle\Model;

class TagService {

    private $em;
    private $tag_class;

    public function __construct($em, $tag_class)
    {
        $this->em = $em;
        $this->tag_class = $tag_class;
    }

    public function getTag($slug)
    {
        $tag = $this->em->getRepository($this->tag_class)->findTagBySlug($slug);
        return $tag;
    }

    public function getTagByText($text)
    {
        return $this->em->getRepository($this->tag_class)->findOneBy(['text' => $text]);
    }

    public function createTag()
    {
        return new $this->tag_class;
    }

    public function saveTag($text)
    {
        $tag = $this->createTag();
        $tag->setText($text);
        $this->em->persist($tag);
        $this->em->flush();
        return $tag;
    }

    public function findAll()
    {
        return $this->em->getRepository($this->tag_class)->findAll();
    }
}
