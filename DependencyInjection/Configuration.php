<?php

namespace ADW\SliderBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 * Project adw/slider-bundle
 * @package ADW\SliderBundle\DependencyInjection
 * @author Anton Prokhorov
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {

        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('adw_slider');

        $rootNode
            ->children()
                ->scalarNode('media_context')
                    ->defaultValue('slider')
                ->end()
            ->end()
//            ->children()
//            ->arrayNode('strateges')
//            ->prototype('scalar')->end()
//                    ->defaultValue(
//                        [
//                            'ADW\SliderBundle\CityStrategy\MoscowCityStrategy',
//                            'ADW\SliderBundle\CityStrategy\SaintPetersburgCityStrategy'
//                        ]
//                    )
//            ->end()
//            ->end()
        ;


        return $treeBuilder;
    }
}