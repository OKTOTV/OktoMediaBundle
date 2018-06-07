<?php

namespace Okto\MediaBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Okto\MediaBundle\Entity\Tag;

/**
 * @Route("/backend/oktolab_media/tag")
 */
class TagController  extends Controller {

    /**
     * TODO: use elasticsearch
     * @Route("/ajax", name="okto_tag_ajax")
     * @Method({"GET"})
     */
    public function ajaxTagAction(Request $request)
    {
        // if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();
            if ($request->getMethod() == "GET") {
                $tags = $this->get('okto_media_tag')->findAll();
                $json = [];
                foreach ($tags as $tag) {
                    $json[] = $tag->getText();
                }
                return new Response(json_encode($json));
            }
        // }
        return $this->redirect('oktothek_tag_index');
    }

    /**
     * @Route("/{slug}/episodes", name="okto_tag_episodes")
     * @Method({"GET"})
     * @Template()
     */
    public function episodesAction(Request $request, $slug)
    {
        $em = $this->getDoctrine()->getManager();
        $tag = $this->get('okto_media_tag')->getTag($slug);
        $query = $em->getRepository(
                $this->getParameter('okto_media.tag_class')
            )->findEpisodesWithTag(
                $tag,
                0,
                true,
                $this->getParameter('oktolab_media.episode_class')
            );
        $paginator = $this->get('knp_paginator');
        $episodes = $paginator->paginate(
            $query,
            $request->query->get('page', 1),
            $request->query->get('results', 12)
        );

        return ['episodes' => $episodes, 'tag' => $tag];
    }

    /**
     * @Route("/{slug}/series", name="okto_tag_series"))
     * @Method({"GET"})
     * @Template()
     */
    public function seriesAction(Request $request, $slug)
    {
        $em = $this->getDoctrine()->getManager();
        $tag = $this->get('okto_media_tag')->getTag($slug);
        $query = $em->getRepository(
                $this->getParameter('okto_media.tag_class')
            )->findSeriesWithTag(
                $tag,
                0,
                true,
                $this->getParameter('oktolab_media.series_class')
            );
        $paginator = $this->get('knp_paginator');
        $seriess = $paginator->paginate(
            $query,
            $request->query->get('page', 1),
            $request->query->get('results', 12)
        );

        return ['seriess' => $seriess, 'tag' => $tag];
    }
}
