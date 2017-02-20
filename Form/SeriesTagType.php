<?php

namespace Okto\MediaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

use Okto\MediaBundle\Form\DataTransformer\TagTransformer;
use Okto\MediaBundle\Form\UserType;

class SeriesTagType extends AbstractType
{
    private $series_class;

    public function __construct($series_class)
    {
        $this->series_class = $series_class;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('tags', TagType::class, ['attr' => ['placeholder' => 'okto_media.series_tag_edit_placeholder']]);
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(['data_class' => $this->series_class]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'okto_mediabundle_series_tag';
    }
}

 ?>
