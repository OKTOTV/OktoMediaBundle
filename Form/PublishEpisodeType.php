<?php

namespace Okto\MediaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PublishEpisodeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('publish', SubmitType::class, ['attr' => ['class' => 'btn btn-default'], 'label' => 'oktothek_publish_episode_button'])
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Oktolab\MediaBundle\Entity\Episode'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'oktolab_mediabundle_publish_episode';
    }
}
