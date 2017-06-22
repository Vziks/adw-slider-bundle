<?php

namespace ADW\SliderBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class City
 * Project adw/slider-bundle
 * @package ADW\SliderBundle\Entity
 * @author Anton Prokhorov
 *
 * @ORM\Entity()
 * @ORM\Table(name="adw_slide_city")
 */
class City
{
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
     * @var string
     *
     * @ORM\Column(type="string", unique=true)
     */
    protected $prefix;

    /**
     * @var RelatedCity[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="ADW\SliderBundle\Entity\RelatedCity")
     */
    private $relatedCitys;


    public function __construct()
    {
        $this->relatedCitys = new ArrayCollection();
    }

    /**
     * @return string
     */
    function __toString()
    {
      return $this->getName();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return City
     */
    public function setId($id)
    {
        $this->id = $id;
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
     * @return City
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * @param string $prefix
     * @return City
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRelatedCitys()
    {
        return $this->relatedCitys;
    }

    /**
     * @param mixed $relatedCitys
     * @return City
     */
    public function setRelatedCitys($relatedCitys)
    {
        $this->relatedCitys = new ArrayCollection();
        foreach ($relatedCitys as $relatedCity) {
            $this->addRelatedCity($relatedCity);
        }
        return $this;
    }

    /**
     * @param RelatedCity $relatedCity
     * @return $this
     */
    public function addRelatedCity($relatedCity)
    {
        $relatedCity->setCity($this);
        $this->relatedCitys->add($relatedCity);

        return $this;
    }

    /**
     * @param RelatedCity $relatedCity
     * @return $this
     */
    public function removeRelatedCity($relatedCity)
    {
        $this->relatedCitys->removeElement($relatedCity);

        return $this;
    }




}