<?php

namespace Okto\MediaBundle\Model;

use Bprs\CommandLineBundle\Model\BprsContainerAwareJob;

class ExtractPosterframeJob extends BprsContainerAwareJob {

    public function perform()
    {
        // get episode information
        $episode = $this->getContainer()->get('oktolab_media')->getEpisode($this->args['uniqID']);

        // get path to video
        $uri = $this->getContainer()->get('bprs.asset_helper')->getAbsoluteUrl($episode->getVideo());

        // generate cache asset
        $posterframe = $this->createCacheAsset($episode);

        // get local path to cache asset
        $path = $this->getContainer()->get('bprs.asset_helper')->getPath($posterframe, true);

        // build ffmpeg command to extract posterframe at position from video to cache asset
        $cmd = sprintf('ffmpeg -i %s -ss %s -vframes 1 "%s"', $uri, $this->args['position'], $path);
        exec($cmd);

        // delete old posterframe
        $this->getContainer()->get('oktolab_media_helper')->deleteEpisodePosterframe($episode);

        // persist new posterframe
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $episode->setPosterframe($posterframe);
        $em->persist($episode);
        $em->persist($posterframe);
        $em->flush();

        // move from cache to posterframe adapter
        $this->getContainer()->get('bprs.asset_job')->addMoveAssetJob(
            $posterframe,
            $this->getContainer()->getParameter(
                'oktolab_media.posterframe_filesystem'
            )
        );
    }

    public function createCacheAsset($episode)
    {
        $asset = $this->getContainer()->get('bprs.asset')->createAsset();
        $asset->setFilekey(
            sprintf('%s.%s',$asset->getFilekey(), 'jpg')
        );
        $asset->setAdapter(
            $this->getContainer()->getParameter(
                'oktolab_media.encoding_filesystem'
            )
        );
        $asset->setName($episode->getVideo()->getName().'_posterframe');
        $asset->setMimetype('image/jpeg');

        return $asset;
    }

}
 ?>
