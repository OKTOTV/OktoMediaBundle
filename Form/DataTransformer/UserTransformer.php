<?php
namespace Okto\MediaBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class UserTransformer implements DataTransformerInterface
{
    private $repository;

    /**
     * @param ObjectManager $om
     */
    public function __construct(\Doctrine\ORM\EntityRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Transforms object to a string.
     *
     * @param  user|null $users
     * @return string
     */
    public function transform($users)
    {
        if (null == $users) {
            return "";
        }

        $texts = [];
        foreach ($users as $user) {
            $texts[] = $user->getUsername();
        }

        return $texts;
    }

    /**
     * Transforms a string (name) to an object (user).
     *
     * @param  string $text
     *
     * @return users|null
     *
     * @throws TransformationFailedException if object (user) is not found.
     */
    public function reverseTransform($texts)
    {
        if (!$texts) {
            return null;
        }

        $users = [];
        foreach ($texts as $text) {
            $user = $this->repository->findOneBy(['username' => $text]);
            if (null === $user) {
                throw new TransformationFailedException(sprintf(
                    'A user with username "%s" does not exist!',
                    $text
                ));
            }
            $users[] = $user;
        }

        return $users;
    }
}
