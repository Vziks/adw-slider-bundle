<?php

namespace ADW\SliderBundle\Controller;


use ADW\SliderBundle\Entity\Slide;
use Application\Sonata\MediaBundle\Entity\Media;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Debug\Exception\ContextErrorException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;

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

        $months = [
            'янв.' => 'January',
            'февр.' => 'February',
            'мар.' => 'March',
            'апр.' => 'April',
            'мая' => 'May',
            'июня' => 'June',
            'июля' => 'July',
            'авг.' => 'August',
            'сент.' => 'September',
            'окт.' => 'October',
            'нояб.' => 'November',
            'дек.' => 'December'
        ];


        $em = $this->getDoctrine()->getManager();

        if (!$request->get('slideId')) {

            $slide = new Slide();

            $slider = $em->getRepository('ADWSliderBundle:Slider')->findOneBy(['id' => $request->get('sliderId')]);

            $slide->setSlider($slider);

            $slide->setName($request->get('slideName', 'slideImage'));

            $slide->setSort($request->get('slidePosition'));

            if ($request->get('isHTML') == 'true') {
                $slide->setType(Slide::TYPE_TEXT);
                $slide->setText($request->get('slideHTML'));
            } else {
                $slide->setType(Slide::TYPE_IMG);
            }

            if ($request->get('url')) {
                $slide->setUrl($request->get('url'));
            }

            if ($request->get('isForAuthorized') == 'true') {
                $slide->setIsUser(true);
            }

            if ($request->get('imageName')) {

                if ($this->check_base64_image($request->get('imageSrc'))) {

                    $mediaManager = $this->container->get('sonata.media.manager.media');

                    $media = new Media();

                    $file = $this->base64ToImage($request->get('imageSrc'), $request->get('imageName'));

                    $media->setBinaryContent($file);
                    $media->setContext('slider');
                    $media->setProviderName('sonata.media.provider.image');
                    $media->setEnabled(true);
                    $mediaManager->save($media, true);

                    $slide->setMedia($media);

                    $slide->setType(Slide::TYPE_IMG);
                }
            }

            if ($request->get('geo')) {

                $citys = [];

                foreach ($geos = $request->get('geo') as $geo) {
                    $city = $em->getRepository('ADWSliderBundle:City')->findOneBy(['id' => $geo['id']]);
                    $citys[] = $city;
                }

                $slide->setCitys($citys);
            }


            if ($request->get('interval')) {
                $slide->setDelay($request->get('interval'));
            }

            if ($request->get('isHidden') == 'true') {

                $slide->setStatus(Slide::STATUS_HIDE);
            } elseif ($request->get('isTemporary') == 'true') {

                $slide->setStatus(Slide::STATUS_TIME);

                $date_starttime_string = str_ireplace(
                    array_keys($months),
                    array_values($months),
                    str_replace(' г., ', ' ', $request->get('startTime'))
                );

                $date_endtime_string = str_ireplace(
                    array_keys($months),
                    array_values($months),
                    str_replace(' г., ', ' ', $request->get('endTime'))
                );

                $ymds = \DateTime::createFromFormat('d M Y H:i:s', $date_starttime_string);
                $ymde = \DateTime::createFromFormat('d M Y H:i:s', $date_endtime_string);

                $slide->setPublicationStartDate($ymds);
                $slide->setPublicationEndDate($ymde);

            } else {

                $slide->setStatus(Slide::STATUS_SHOW);

                if ($this->check_base64_image($request->get('imageSrc'))) {

                    $mediaManager = $this->container->get('sonata.media.manager.media');

                    $media = new Media();
                    $file = $this->base64ToImage($request->get('imageSrc'), $request->get('imageName'));

                    $media->setBinaryContent($file);
                    $media->setContext('slider');
                    $media->setProviderName('sonata.media.provider.image');
                    $media->setEnabled(true);
                    $mediaManager->save($media, true);

                    $slide->setMedia($media);
                }

            }

            $em->persist($slide);
            $em->flush();

            $response['response'] = ['slideId' => $slide->getId()];

            return new JsonResponse($response);

        } else {

            $slide = $em->getRepository('ADWSliderBundle:Slide')->findOneBy(['id' => $request->get('slideId')]);

            $slider = $em->getRepository('ADWSliderBundle:Slider')->findOneBy(['id' => $request->get('sliderId')]);

            $slide->setSlider($slider);

            $slide->setName($request->get('slideName', 'slideImage'));

            $slide->setSort($request->get('slidePosition'));

            if ($request->get('isHTML') == 'true') {
                $slide->setType(Slide::TYPE_TEXT);
                $slide->setText($request->get('slideHTML'));
            } else {
                $slide->setType(Slide::TYPE_IMG);
            }

            if ($request->get('url')) {
                $slide->setUrl($request->get('url'));
            }

            if ($request->get('isForAuthorized') == 'true') {
                $slide->setIsUser(true);
            }

            if ($request->get('imageName')) {

                if ($this->check_base64_image($request->get('imageSrc'))) {

                    $mediaManager = $this->container->get('sonata.media.manager.media');

                    $media = new Media();

                    $file = $this->base64ToImage($request->get('imageSrc'), $request->get('imageName'));

                    $media->setBinaryContent($file);
                    $media->setContext('slider');
                    $media->setProviderName('sonata.media.provider.image');
                    $media->setEnabled(true);
                    $mediaManager->save($media, true);

                    $slide->setMedia($media);

                    $slide->setType(Slide::TYPE_IMG);
                }
            }

            if ($request->get('geo')) {

                $citys = [];

                foreach ($geos = $request->get('geo') as $geo) {
                    $city = $em->getRepository('ADWSliderBundle:City')->findOneBy(['id' => $geo['id']]);
                    $citys[] = $city;
                }

                $slide->setCitys($citys);
            }


            if ($request->get('interval')) {
                $slide->setDelay($request->get('interval'));
            }

            if ($request->get('isHidden') == 'true') {

                $slide->setStatus(Slide::STATUS_HIDE);
            } elseif ($request->get('isTemporary') == 'true') {

                $slide->setStatus(Slide::STATUS_TIME);

                $date_starttime_string = str_ireplace(
                    array_keys($months),
                    array_values($months),
                    str_replace(' г., ', ' ', $request->get('startTime'))
                );

                $date_endtime_string = str_ireplace(
                    array_keys($months),
                    array_values($months),
                    str_replace(' г., ', ' ', $request->get('endTime'))
                );

                $ymds = \DateTime::createFromFormat('d M Y H:i:s', $date_starttime_string);
                $ymde = \DateTime::createFromFormat('d M Y H:i:s', $date_endtime_string);

                $slide->setPublicationStartDate($ymds);
                $slide->setPublicationEndDate($ymde);

            } else {

                $slide->setStatus(Slide::STATUS_SHOW);


                if ($this->check_base64_image($request->get('imageSrc'))) {

                    $mediaManager = $this->container->get('sonata.media.manager.media');

                    $media = new Media();
                    $file = $this->base64ToImage($request->get('imageSrc'), $request->get('imageName'));

                    $media->setBinaryContent($file);
                    $media->setContext('slider');
                    $media->setProviderName('sonata.media.provider.image');
                    $media->setEnabled(true);
                    $mediaManager->save($media, true);

                    $slide->setMedia($media);
                }

            }

            $em->persist($slide);
            $em->flush();

            $response['response'] = true;

        }


        return new JsonResponse($response);
    }


    /**
     * @param $base64_string
     * @param $output_file
     * @return mixed
     */
    public function base64ToImage($base64_string, $output_file)
    {
        $file = fopen($output_file, "wb");

        $data = explode(',', $base64_string);

        fwrite($file, base64_decode($data[1]));
        fclose($file);

        return $output_file;
    }


    /**
     * @param $base64
     * @return bool
     */
    public function check_base64_image($base64)
    {
        try {
            $img = imagecreatefromstring(base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64)));
        } catch (ContextErrorException $e) {
            return false;
        }

        imagepng($img, 'tmp.png');
        $info = getimagesize('tmp.png');

        unlink('tmp.png');

        if ($info[0] > 0 && $info[1] > 0 && $info['mime']) {
            return true;
        }

        return false;
    }


}