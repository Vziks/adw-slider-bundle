<?php

namespace ADW\SliderBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Application\Sonata\ClassificationBundle\Entity\Context;
use Application\Sonata\ClassificationBundle\Entity\Category;


/**
 * Class LoadSonataMediaData
 * Project adw/slider-bundle
 * @package ADW\SliderBundle\DataFixtures\ORM
 * @author Anton Prokhorov
 */
class LoadSonataMediaData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * @inheritdoc
     */
    public function load(ObjectManager $manager)
    {
        $context = new Context();
        $context->setId('default');
        $context->setName('default');
        $context->setEnabled(true);
        $manager->persist($context);
        $manager->flush();

        $category = new Category();
        $category->setName('Default');
        $category->setContext($context);
        $category->setEnabled(true);
        $manager->persist($category);
        $manager->flush();
    }

    /**
     * @inheritdoc
     */
    public function getOrder()
    {
        return 1;
    }


}