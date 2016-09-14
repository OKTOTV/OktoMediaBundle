<?php

namespace Okto\MediaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Okto\MediaBundle\Entity\Reachme;

class ReachmeType extends AbstractType
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
            ->add('type', ChoiceType::class, [
                'choices' => [
                    Reachme::TYPE_MAIL    => $this->trans->transchoice('reachme_type_transchoice', Reachme::TYPE_MAIL),
                    Reachme::TYPE_FB      => $this->trans->transchoice('reachme_type_transchoice', Reachme::TYPE_FB),
                    Reachme::TYPE_TWITTER => $this->trans->transchoice('reachme_type_transchoice', Reachme::TYPE_TWITTER),
                    Reachme::TYPE_INST    => $this->trans->transchoice('reachme_type_transchoice', Reachme::TYPE_INST),
                    Reachme::TYPE_GP      => $this->trans->transchoice('reachme_type_transchoice', Reachme::TYPE_GP)
                ]
            ])
            ->add('uri', TextType::class, ['attr' => ['placeholder' => 'media.reachme_uri_placeholder']])
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Okto\MediaBundle\Entity\Reachme'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'okto_mediabundle_reachme';
    }
}
