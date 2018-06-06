<?php

namespace Okto\MediaBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

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
     * @Route("", name="okto_tag_episodes")
     * @Method({"GET"})
     */
    public function episodesAction(Request $request)
    {
        // code...
    }

    /**
     * @Route("", name="okto_tag_series"))
     * @Method({"GET"})
     */
    public function seriesAction(Request $request)
    {
        // code...
    }
}
