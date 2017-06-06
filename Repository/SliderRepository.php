<?php

namespace ADW\SliderBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class SliderRepository
 * Project adw/slider-bundle
 * @package ADW\SliderBundle\Repository
 * @author Anton Prokhorov
 */
class SliderRepository extends EntityRepository
{
    /**
     * @return array
     */
    public function findAll()
    {
        return parent::findAll();
    }

    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @param null $limit
     * @param null $offset
     * @return array
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        return parent::findBy($criteria, $orderBy, $limit, $offset);
    }


    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @return null|object
     */
    public function findOneBy(array $criteria, array $orderBy = null)
    {
        return parent::findOneBy($criteria, $orderBy);
    }

    public function getSlider(){

    }


}
