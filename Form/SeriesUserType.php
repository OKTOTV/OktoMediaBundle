<?php

namespace Okto\MediaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

use Okto\MediaBundle\Form\DataTransformer\UserTransformer;
use Okto\MediaBundle\Form\UserType;

class SeriesUserType extends AbstractType
{
    private $repo;

    public function __construct($repo)
    {
        $this->repo = $repo;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('users', CollectionType::class, [
                    'entry_type'        => HiddenType::class,
                    'allow_add'         => true,
                    'allow_delete'      => true,
                    'attr'              => [
                        'placeholder' => 'search usernames',
                        'url'         => 'oktolab_series_user_by_username'
                    ]
            ]);

        $UserTransformer = new UserTransformer($this->repo);

        $builder->get('users')->addModelTransformer($UserTransformer);
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MediaBundle\Entity\Series',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'okto_mediabundle_series_user';
    }
}

 ?>
