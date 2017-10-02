# ADW Slider Bundle #



### Installation ###

Add repository

```
"repositories": [
    {"type": "vcs", "url": "https://bitbucket.org/prodhub/adw-geoip-bundle.git"},
    {"type": "vcs", "url": "https://bitbucket.org/prodhub/slider-bundle.git"}    
]
```

Install vendor

```
$ composer require adw/slider-bundle '^1.0'
```

Enable bundle for framework

```
app/AppKernel.php
...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            ...
            new ADW\GeoIpBundle\ADWGeoIpBundle(),
            new ADW\SliderBundle\ADWSliderBundle(),
            ...
        );
```
Install asset

```
$ php app/console asset:install
```

Routing

```
app/config/routing.yml
...
adw_slider:
    resource: "@ADWSliderBundle/Controller/"
    type:     annotation
    prefix:   /
```

Migration DB schema

```
$ php app/console doctrine:migration:diff
$ php app/console doctrine:migration:migrate
```

Update DB schema

```
$ php app/console doctrine:schema:update --force
```

Config

```
 Если требуется сохранять под определенным контекстом sonata-media, по умолчанию контекст 'default' 
adw_slider:
    media_context: slider
        
# Sonata Media Configuration
sonata_media:
        slider:  # the slider context is mandatory
            providers:                
                - sonata.media.provider.image                

            formats:
                small: { width: 100 , quality: 70}
                big:   { width: 500 , quality: 70}        
        
```

### Usage Twig ###

```
{{ adw_slider('slidermain', 'Имя шаблона') }}
где "slidermain" системное имя слайдера, по умолчанию ADWSliderBundle:Default:slider.html.twig
```

### Tests ###

```
$ bin/phpunit -c app vendor/adw/slider-bundle --tap
```

### Preupdate data ###

```
$ php app/console adw:ipgeobase:create - Создать Гео БД
$ php app/console adw:slider:urcity:create - Создать пользовательскую БД с городами
```