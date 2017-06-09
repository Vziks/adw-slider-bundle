<?php
namespace ADW\SliderBundle\DataFixtures\ORM;

use ADW\SliderBundle\Entity\Slide;
use ADW\SliderBundle\Entity\Slider;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Application\Sonata\MediaBundle\Entity\Media;


/**
 * Class LoadSliderData
 * Project adw/slider-bundle
 * @package ADW\SliderBundle\DataFixtures\ORM
 * @author Anton Prokhorov
 */
class LoadSliderData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    /**
     * @inheritdoc
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('ru_RU');


            $slider = new Slider();

            $slider->setName($faker->company);
            $slider->setDescription('Slider Description');
            $slider->setSysName('slidermain');


            for ($i = 0; $i < 5; $i++) {

                $mediaManager = $this->container->get('sonata.media.manager.media');

                $media = new Media();

                $media->setBinaryContent($faker->image($dir = '/tmp', $width = 100, $height = 100, 'people'));
                $media->setContext('default');
                $media->setProviderName('sonata.media.provider.image');
                $media->setEnabled(true);
                $mediaManager->save($media, true);

                $PositionedSlideMedia = new Slide($media , rand(0, 5));
                $PositionedSlideMedia->setName($faker->jobTitle);
                $PositionedSlideMedia->setStatus(Slide::STATUS_SHOW);

                $slider->addSlide($PositionedSlideMedia);

            }

            $manager->persist($slider);


        $manager->flush();
    }


    /**
     * @inheritdoc
     */
    public function getOrder()
    {
        return 2;
    }

}