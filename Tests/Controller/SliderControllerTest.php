<?php

namespace ADW\SliderBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * Class SliderControllerTest
 * Project adw/slider-bundle
 * @package ADW\SliderBundle\Tests\Controller
 * @author Anton Prokhorov
 */
class SliderControllerTest extends WebTestCase
{
    protected function setUp()
    {
        $this->loadFixtures([
            'ADW\SliderBundle\DataFixtures\ORM\LoadSonataMediaData',
            'ADW\SliderBundle\DataFixtures\ORM\LoadSliderData'
        ]);
    }

    protected function tearDown()
    {
        $this->loadFixtures([]);
        parent::tearDown();
    }

    public function testSliderHomepage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/slider-test/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertGreaterThan(
            0,
            $crawler->filter('div.image')->count()
        );

        return $client;
    }

}