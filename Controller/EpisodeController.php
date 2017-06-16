<?php

namespace Okto\MediaBundle\Controller;

use Oktolab\MediaBundle\Controller\EpisodeController as BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Okto\MediaBundle\Entity\Episode;
use Okto\MediaBundle\Form\EpisodeType;
use MediaBundle\Form\PublishEpisodeType;
use AppBundle\Entity\Notification;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Episode controller.
 *
 * @Route("/backend/oktolab_media/episode")
 */
class EpisodeController extends BaseController
{
    /**
     * Displays a form to edit an existing Episode entity.
     * @Route("/{episode}/edit", name="oktolab_episode_edit")
     * @ ParamConverter("episode", class="OktoMediaBundle:Episode")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function editAction(Request $request, $episode)
    {
        $episode = $this->get('oktolab_media')->getEpisode($episode);
        $form = $this->createForm(EpisodeType::class, $episode);
        $form->add('submit', SubmitType::class, ['label' => 'oktolab_media.edit_episode_button', 'attr' => ['class' => 'btn btn-primary']]);
        $form->add('submitAndEncode', SubmitType::class, ['label' => 'okto_media.edit_episode_submitAndEncode_button', 'attr' => ['class' => 'btn btn-default']]);
        $form->add('delete', SubmitType::class, ['label' => 'oktolab_media.delete_episode_button', 'attr' => ['class' => 'btn btn-danger']]);

        if ($request->getMethod() == "POST") { //sends form
            $form->handleRequest($request);
            $em = $this->getDoctrine()->getManager();
            if ($form->isValid()) { //form is valid, save or preview
                if ($form->get('submit')->isClicked()) { //save me
                    $em->persist($episode);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', 'oktolab_media.success_edit_episode');
                    return $this->redirect($this->generateUrl('oktolab_episode_show', ['uniqID' => $episode->getUniqID()]));
                } elseif ($form->get('submitAndEncode')->isClicked()) {
                    $em->persist($episode);
                    $em->flush();
                    $this->get('oktolab_media')->addEncodeVideoJob($episode->getUniqID());
                    $this->get('session')->getFlashBag()->add('success', 'okto_media.success_edit_and_encode_episode');
                    return $this->redirect($this->generateUrl('oktolab_episode_show', ['uniqID' => $episode->getUniqID()]));
                } elseif ($form->get('delete')->isClicked()) {
                    $em->remove($episode);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', 'oktolab_media.success_delete_episode');
                    if ($episode->getSeries()) {
                        return $this->redirect($this->generateUrl('oktolab_series_show', ['series' => $episode->getSeries()->getUniqID()]));
                    } else {
                        return $this->redirect($this->generateUrl('oktolab_series'));
                    }
                } else { //???
                    $this->get('session')->getFlashBag()->add('success', 'oktolab_media.info_edit_episode_unknown_button');
                    return $this->redirect($this->generateUrl('oktolab_episode_show', ['uniqID' => $episode->getUniqID()]));
                }
            }
            $this->get('session')->getFlashBag()->add('error', 'oktolab_media.error_edit_episode');
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Template()
     */
    public function newAction(Request $request)
    {
        $episode = $this->get('oktolab_media')->createEpisode();
        $form = $this->createForm(EpisodeType::class, $episode);
        $form->add('submit', SubmitType::class, ['label' => 'oktolab_media.create_episode_button', 'attr' => ['class' => 'btn btn-primary']]);
        $form->add('submitAndEncode', SubmitType::class, ['label' => 'okto_media.create_episode_submitAndEncode_button', 'attr' => ['class' => 'btn btn-default']]);

        if ($request->getMethod() == "POST") { //sends form
            $form->handleRequest($request);
            $em = $this->getDoctrine()->getManager();
            if ($form->isValid() || $form->get('delete')->isClicked()) { //form is valid, save or preview
                if ($form->get('submit')->isClicked()) { //save me
                    $em->persist($episode);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', 'oktolab_media.success_edit_episode');
                    return $this->redirect($this->generateUrl('oktolab_episode_show', ['uniqID' => $episode->getUniqID()]));
                } elseif ($form->get('submitAndEncode')->isClicked()) {
                    $em->persist($episode);
                    $em->flush();
                    $this->get('oktolab_media')->addEncodeVideoJob($episode->getUniqID());
                    $this->get('session')->getFlashBag()->add('success', 'okto_media.success_create_and_encode_episode');
                    return $this->redirect($this->generateUrl('oktolab_episode_show', ['uniqID' => $episode->getUniqID()]));
                } elseif ($form->get('delete')->isClicked()) {
                    $em->remove($episode);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', 'oktolab_media.success_delete_episode');
                    return $this->redirect($this->generateUrl('backend'));
                } else { //???
                    $this->get('session')->getFlashBag()->add('success', 'oktothek.info_edit_episode_unknown_button');
                    return $this->redirect($this->generateUrl('oktolab_episode_show', ['uniqID' => $episode->getUniqID()]));
                }
            }
            $this->get('session')->getFlashBag()->add('error', 'oktothek.error_edit_episode');
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/{uniqID}/extractPosterframe", name="oktolab_episode_extractposterframe")
     * @Method({"GET"})
     */
    public function extractPosterframeAction(Request $request, $uniqID)
    {
        $this->get('okto_media')->addExtractPosterfameJob($uniqID, $request->query->get('position', 0));
        $this->get('session')->getFlashBag()->add('success', 'oktolab_media.success_extractPosterframe_job');
        return $this->redirect($this->generateUrl('oktolab_episode_show', ['uniqID' => $uniqID]));
    }
}
