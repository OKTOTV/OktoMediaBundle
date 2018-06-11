<?php

namespace Okto\MediaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Okto\MediaBundle\Model\MediaService;

class SeriesStateType extends AbstractType
{
    private $trans;

    public function __construct($trans)
    {
        $this->trans = $trans;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('state', ChoiceType::class, [
                'choices' => [
                    $this->trans->transchoice('okto_media_series_state_transchoice', MediaService::SERIES_STATE_ALL_ACTIVE) => MediaService::SERIES_STATE_ALL_ACTIVE,
                    $this->trans->transchoice('okto_media_series_state_transchoice', MediaService::SERIES_STATE_ALL_INACTIVE) => MediaService::SERIES_STATE_ALL_INACTIVE,
                    $this->trans->transchoice('okto_media_series_state_transchoice', MediaService::SERIES_STATE_SERIES_ACTIVE_ONLY) => MediaService::SERIES_STATE_SERIES_ACTIVE_ONLY,
                    $this->trans->transchoice('okto_media_series_state_transchoice', MediaService::SERIES_STATE_EPISODES_ACTIVE_ONLY) => MediaService::SERIES_STATE_EPISODES_ACTIVE_ONLY,
                ],
                'mapped' => false
            ])
        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'okto_mediabundle_reachme';
    }
}
