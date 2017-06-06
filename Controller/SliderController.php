<?php

namespace ADW\SliderBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class SliderController
 * Project adw/slider-bundle
 * @package ADW\SliderBundle\Controller
 * @author Anton Prokhorov
 */
class SliderController extends Controller
{
    /**
     * @Route("/slider-test/")
     */
    public function sliderTestAction()
    {
        return $this->render('ADWSliderBundle:Default:index.html.twig');
    }

    /**
     * @Route("/slider/")
     */
    public function sliderAction()
    {
        return $this->render('ADWSliderBundle:Default:index.html.twig');
    }
}
