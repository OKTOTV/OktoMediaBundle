<?php

namespace Okto\MediaBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use Okto\MediaBundle\DependencyInjection\Compiler\OverrideServiceCompilerPass;

class OktoMediaBundle extends Bundle
{
    public function getParent()
    {
        return 'OktolabMediaBundle';
    }

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new OverrideServiceCompilerPass());
    }
}
