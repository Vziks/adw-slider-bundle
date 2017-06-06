<?php

namespace ADW\SliderBundle\Traits;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Trait AdminServiceContainerTrait
 * Project adw/slider-bundle
 * @package ADW\SliderBundle\Trait
 * @author Anton Prokhorov
 */
trait AdminServiceContainerTrait
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @param ContainerInterface $container
     */
    public function setServiceContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }

}