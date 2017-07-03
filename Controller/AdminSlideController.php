<?php

namespace ADW\SliderBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AdminSlideController
 * Project adw/slider-bundle
 * @package ADW\SliderBundle\Controller
 * @author Anton Prokhorov
 */
class AdminSlideController extends Controller
{
    /**
     * @Route("/admin/adw/slider/actions", name="adw_slider_slide_actions")
     * @param Request $request
     * @return JsonResponse
     */
    public function slideSaveAction(Request $request)
    {
        $response = [];

        $response['response'] = true;

        return new JsonResponse($response);
    }
}