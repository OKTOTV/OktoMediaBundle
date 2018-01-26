<?php

namespace Okto\MediaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Oktolab\MediaBundle\Model\MediaService;

use Bprs\AppLinkBundle\Entity\Keychain;
/**
 * Keychain controller. Allows backend importing onsite.
 *
 * @Route("/oktolab_media")
 * @Security("has_role('ROLE_OKTOLAB_MEDIA_READ')")
 */
class KeychainController extends Controller
{



    /**
     * @Route("/show_keychain/{keychain}/series/{uniqID}", name="oktolab_media_show_keychain_series")
     * @Template()
     */
    public function showSeriesAction(Keychain $keychain, $uniqID)
    {
        $series = $this->get('oktolab_keychain')->getSeries(
            $keychain,
            $uniqID
        );
        return ['keychain' => $keychain, 'series' => $series];
    }
}
