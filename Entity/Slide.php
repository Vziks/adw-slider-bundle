<?php
namespace ADW\SliderBundle\Entity;

use ADW\CommonBundle\Model\AdvancedPublicationTrait;
use Application\Sonata\MediaBundle\Entity\Media;
use Doctrine\Common\Collections\ArrayCollection;
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

    const TYPE_IMG = 'img';
    const TYPE_TEXT = 'text';

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
     * @var string
     *
     * @ORM\Column(type="string", length=4)
     */
    protected $type = Slide::TYPE_IMG;


    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $is_user = false;


    /**
     * @var City[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="ADW\SliderBundle\Entity\City")
     */
    protected $citys;


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

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Slide
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsUser()
    {
        return $this->is_user;
    }

    /**
     * @param mixed $is_user
     * @return Slide
     */
    public function setIsUser($is_user)
    {
        $this->is_user = $is_user;
        return $this;
    }

    /**
     * Add city
     *
     * @param \ADW\SliderBundle\Entity\City $city
     *
     * @return Slide
     */
    public function addTag(\ADW\SliderBundle\Entity\City $city)
    {
        $this->citys[] = $city;

        return $this;
    }

    /**
     * Remove city
     *
     * @param \ADW\SliderBundle\Entity\City $city
     */
    public function removeTag(\ADW\SliderBundle\Entity\City $city)
    {
        $this->citys->removeElement($city);
    }

    /**
     * @return City[]|ArrayCollection
     */
    public function getCitys()
    {
        return $this->citys;
    }

    /**
     * @param City[]|ArrayCollection $citys
     * @return Slide
     */
    public function setCitys($citys)
    {
        $this->citys = $citys;
        return $this;
    }


}