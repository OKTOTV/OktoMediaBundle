<?php
namespace Okto\MediaBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class TagTransformer implements DataTransformerInterface
{

    private $tag_service;

    /**
     * @param ObjectManager $om
     */
    public function __construct($tagservice)
    {
        $this->tag_service = $tagservice;
    }

    /**
     * Transforms object (assets) to a string (key).
     *
     * @param  Tag|null $tag
     * @return string
     */
    public function transform($tags)
    {
        // die(var_dump($tags));
        if (null === $tags) {
            return null;
        }
        $texts = [];

        foreach ($tags as $tag) {
            $texts[] = $tag->getText();
        }

        return $texts;
    }

    /**
     * Transforms a string (key) to an object (asset).
     *
     * @param  string $text
     *
     * @return Tag|null
     *
     * @throws TransformationFailedException if object (tag) is not found.
     */
    public function reverseTransform($texts)
    {
        // die(var_dump($texts));
        if (!$texts) {
            return null;
        }
        $tags = array();
        foreach ($texts as $text) {
            $tag = $this->tag_service->getTagByText($text);
            if (null === $tag) {
                $tag = $this->tag_service->saveTag($text);
            }
            $tags[] = $tag;
        }

        return $tags;
    }
}
