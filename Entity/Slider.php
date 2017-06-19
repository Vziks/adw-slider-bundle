<?php

namespace ADW\SliderBundle\Entity;

use ADW\CommonBundle\Model\AdvancedPublicationTrait;
use ADW\CommonBundle\Model\PublicationTrait;
use Application\Sonata\MediaBundle\Entity\Media;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\PersistentCollection;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Slider
 * Project adw/slider-bundle
 * @package ADW\SliderBundle\Entity
 * @author Anton Prokhorov
 *
 * @ORM\Entity(repositoryClass="ADW\SliderBundle\Repository\SliderRepository")
 * @ORM\Table(name="adw_slider")
 */
class Slider
{
    use Timestampable;

    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $is_show = false;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    protected $height;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    protected $width;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $description;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $sysName;

    /**
     * @ORM\OneToMany(targetEntity="ADW\SliderBundle\Entity\Slide", mappedBy="slider", cascade={"persist", "remove"}, orphanRemoval=true)
     * @Orm\OrderBy({"sort" = "ASC"})
     */
    private $slides;

    /**
     * Slider constructor.
     */
    public function __construct()
    {
        $this->slides = new ArrayCollection();
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
     * @return Slider
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Slider
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getSysName()
    {
        return $this->sysName;
    }

    /**
     * @param string $sysName
     * @return Slider
     */
    public function setSysName($sysName)
    {
        $this->sysName = $sysName;
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param ArrayCollection $slides
     *
     * @return $this
     */
    public function setSlides($slides)
    {
        $this->questions = new ArrayCollection();
        foreach ($slides as $slide) {
            $this->addSlide($slide);
        }
        return $this;
    }

    /**
     * @param Slide $slide
     * @return $this
     */
    public function addSlide($slide)
    {
        $slide->setSlider($this);
        $this->slides->add($slide);

        return $this;
    }

    /**
     * @param Slide $slide
     * @return $this
     */
    public function removeSlide($slide)
    {
        $this->slides->removeElement($slide);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSlides()
    {
        return $this->slides;
    }

    /**
     * @return mixed
     */
    public function getIsShow()
    {
        return $this->is_show;
    }

    /**
     * @param mixed $is_show
     * @return Slider
     */
    public function setIsShow($is_show)
    {
        $this->is_show = $is_show;
        return $this;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param int $height
     * @return Slider
     */
    public function setHeight($height)
    {
        $this->height = $height;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param mixed $width
     * @return Slider
     */
    public function setWidth($width)
    {
        $this->width = $width;
        return $this;
    }

}