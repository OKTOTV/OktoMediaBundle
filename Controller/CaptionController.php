<?php

namespace Okto\MediaBundle\Controller;

use Oktolab\MediaBundle\Controller\CaptionController as BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Oktolab\MediaBundle\Entity\Caption;
use Oktolab\MediaBundle\Form\CaptionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Caption controller.
 *
 * @Route("/oktolab_media/caption")
 */
class CaptionController extends BaseController
{

    /**
     * @Route("/captionEditor/{uniqID}", name="oktolab_capiton_editor")
     * @Template()
     */
    public function captionEditorAction(Request $request, Caption $caption)
    {
        $form = $this->createForm(CaptionType::class, $caption);
        $form->add(
            'delete',
            SubmitType::class,
            [
                'label' => 'oktolab_media.delete_caption_button',
                'attr' => ['class' => 'btn btn-link']
            ]
        );
        $form->add(
            'submit',
            SubmitType::class,
            [
                'label' => 'oktolab_media.update_caption_button',
                'attr' => ['class' => 'btn btn-primary']
            ]
        );

        if ($request->getMethod() == "POST") {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                if ($form->get('delete')->isClicked()) {
                    $uniqID = $caption->getEpisode()->getUniqID();
                    $em->remove($caption);
                    $em->flush();
                    $this
                        ->get('session')
                        ->getFlashBag()
                        ->add('success', 'oktolab_media.success_delete_caption');
                    return $this->redirect(
                        $this->generateUrl(
                            'oktolab_episode_show',
                            ['uniqID' => $uniqID]
                            )
                        );
                } else {
                    $em->persist($caption);
                    $em->flush();
                    $this
                        ->get('session')
                        ->getFlashBag()
                        ->add('success', 'oktolab_media.success_update_caption');
                    return $this->redirect(
                        $this->generateUrl(
                            'oktolab_caption_show',
                            ['caption' => $caption->getId()]
                            )
                        );
                }
            }
            $this->get('session')->getFlashBag()->add('error', 'oktolab_media.error_edit_caption');
        }

        return ['form' => $form->createView(), 'caption' => $caption];
    }
}
