<?php
namespace ADW\SliderBundle\Entity;

use ADW\CommonBundle\Model\AdvancedPublicationTrait;
use Application\Sonata\MediaBundle\Entity\Media;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model\Sortable\Sortable;

/**
 * Class PositionedSlideMedia
 * Project adw/slider-bundle
 * @package ADW\SliderBundle\Entity
 * @author Anton Prokhorov
 *
 * @ORM\Entity()
 * @ORM\Table(name="adw_positioned_slide_media")
 */
class Slide
{

    const STATUS_SHOW = 'show';
    const STATUS_TIME = 'time';
    const STATUS_HIDE = 'hide';

    use Sortable;
    use AdvancedPublicationTrait;

    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Media
     *
     * @ORM\ManyToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media", cascade={"persist"})
     */
    protected $media;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $url;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $text;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $delay = 5;

    /**
     * @ORM\ManyToOne(targetEntity="Slider", inversedBy="slides", cascade={"persist"})
     * @ORM\JoinColumn(name="slider_id", referencedColumnName="id")
     */
    private $slider;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=4)
     */
    protected $status = Slide::STATUS_HIDE;

    /**
     * PositionedSlideMedia constructor.
     *
     * @param Media|null $media
     * @param int|null $sort
     */
    public function __construct(Media $media = null, $sort = 0)
    {
        $this->media = $media;
        $this->sort = $sort;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Media
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * @param Media $media
     */
    public function setMedia($media)
    {
        $this->media = $media;
    }

    /**
     * @return int
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @param int $sort
     * @return Slide
     */
    public function setSort($sort)
    {
        $this->sort = $sort;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Slide
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return Slide
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     * @return Slide
     */
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @return int
     */
    public function getDelay()
    {
        return $this->delay;
    }

    /**
     * @param int $delay
     * @return Slide
     */
    public function setDelay($delay)
    {
        $this->delay = $delay;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return Slide
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSlider()
    {
        return $this->slider;
    }

    /**
     * @param mixed $slider
     * @return Slide
     */
    public function setSlider($slider)
    {
        $this->slider = $slider;
        return $this;
    }



}