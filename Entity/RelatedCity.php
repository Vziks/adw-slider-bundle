<?php

namespace ADW\SliderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class RelatedCity
 * Project adw/slider-bundle
 * @package ADW\SliderBundle\Entity
 * @author Anton Prokhorov
 *
 * @ORM\Entity(repositoryClass="ADW\SliderBundle\Repository\RelatedCityRepository")
 * @ORM\Table(name="adw_slide_related_city")
 */
class RelatedCity
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
     * @ORM\Column(type="string")
     */
    protected $prefix;


    /**
     * @ORM\ManyToOne(targetEntity="City", inversedBy="relatedCitys", cascade={"persist"})
     * @ORM\JoinColumn(name="city_id", referencedColumnName="id")
     */
    private $city;

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
     * @return RelatedCity
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
     * @return RelatedCity
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
     * @return RelatedCity
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     * @return RelatedCity
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

}