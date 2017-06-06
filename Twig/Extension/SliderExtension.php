<?php

namespace ADW\SliderBundle\Twig\Extension;

use ADW\SliderBundle\Entity\Slider;
use ADW\SliderBundle\Repository\SliderRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class SliderExtension
 * Project adw/slider-bundle
 * @package ADW\SliderBundle\Twig\Extension
 * @author Anton Prokhorov
 */
class SliderExtension extends \Twig_Extension
{

    /**
     * @var SliderRepository
     */
    protected $sliderRepository;

    /**
     * @var ContainerInterface
     */
    private $container;


    /**
     * SliderExtension constructor.
     * @param SliderRepository $sliderRepository
     * @param ContainerInterface $container
     */
    public function __construct(SliderRepository $sliderRepository, ContainerInterface $container)
    {
        $this->sliderRepository = $sliderRepository;
        $this->container = $container;
    }


    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            'adw_slider' => new \Twig_SimpleFunction('adw_slider', [$this, 'adw_slider'], ['is_safe' => ['html']])
        ];
    }

    /**
     * @param string $sysName
     * @param string $template
     * @return string
     */
    public function adw_slider($sysName = 'slidermain', $template = 'ADWSliderBundle:Default:slider.html.twig')
    {
        /**
         * @var Slider $slider
         */
        $slider = $this->sliderRepository->findOneBy(['sysName' => $sysName]);

        return $this->container->get('templating')->render($template, [
            'slider' => $slider
        ]);

    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'slider_extension';
    }

}