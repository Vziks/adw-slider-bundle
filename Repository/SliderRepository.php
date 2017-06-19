<?php

namespace ADW\SliderBundle\Repository;

use ADW\SliderBundle\Entity\City;
use ADW\SliderBundle\Traits\AdminServiceContainerTrait;
use ADW\SliderBundle\Traits\RepositoryTokenStorageTrait;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class SliderRepository
 * Project adw/slider-bundle
 * @package ADW\SliderBundle\Repository
 * @author Anton Prokhorov
 */
class SliderRepository extends EntityRepository
{
    use RepositoryTokenStorageTrait;

    use AdminServiceContainerTrait;

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

    public function getSlider($sysName)
    {

        $city = null;

        $qb = $this->createQueryBuilder('s');

        $user = $this->tokenStorage->getToken()->getUser();

        $userIp = $this->getUserIp();


        $userCity = $this->container->get('adw.geoip.handler')->getCity($userIp);

        if($userCity) {
            /**
             * @var EntityManager $em
             */
            $em = $this->container->get('doctrine.orm.default_entity_manager');
            /**
             * @var City $city
             */
            $city = $em->getRepository('ADWSliderBundle:City')->findOneBy(['name' => $userCity]);
        }

        $criterias = [];

        $qb
            ->select('s', 'sl', 'ct')
            ->join('s.slides', 'sl')
            ->leftJoin('sl.citys', 'ct')
            ->where($qb->expr()->eq('s.sysName', ':sName'));

        $criterias[] = $qb->expr()->andx(
            $qb->expr()->eq('sl.status', ':showstatus'),
            ($user ? $qb->expr()->eq('sl.is_user', 0): $qb->expr()->eq('sl.is_user', 1)),
            $qb->expr()->isNull('ct.prefix')
        );

        if($city && $city->getPrefix()) {
            $criterias[] = $qb->expr()->andx(
                $qb->expr()->eq('sl.status', ':showstatus'),
                ($user ? $qb->expr()->eq('sl.is_user', 0) : $qb->expr()->eq('sl.is_user', 1)),
                $qb->expr()->eq('ct.prefix', ':city')
            );
        }

        $criterias[] = $qb->expr()->andx(
            $qb->expr()->eq('sl.status', ':timestatus'),
            $qb->expr()->lte('sl.publicationStartDate', ':cdate'),
            $qb->expr()->gte('sl.publicationEndDate', ':cdate'),
            ($user ? $qb->expr()->eq('sl.is_user', 0): $qb->expr()->eq('sl.is_user', 1)),
            $qb->expr()->isNull('ct.prefix')
        );

        if($city && $city->getPrefix()) {
            $criterias[] = $qb->expr()->andx(
                $qb->expr()->eq('sl.status', ':timestatus'),
                $qb->expr()->lte('sl.publicationStartDate', ':cdate'),
                $qb->expr()->gte('sl.publicationEndDate', ':cdate'),
                ($user ? $qb->expr()->eq('sl.is_user', 0) : $qb->expr()->eq('sl.is_user', 1)),
                $qb->expr()->eq('ct.prefix', ':city')
            );
        }
        $where = \call_user_func_array([$qb->expr(), "orx"], $criterias);

        $qb->andWhere($where);

        $qb->setParameter('showstatus', 'show');
        $qb->setParameter('timestatus', 'time');
        if($city && $city->getPrefix()) {
            $qb->setParameter('city', $city->getPrefix());
        }
        $qb->setParameter('cdate', new \DateTime());
        $qb->setParameter('sName', $sysName);

        $q = $qb->getQuery()->getOneOrNullResult();

        return $q;

    }

    protected function getUserIp() {

        $forwardfor = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : null;

        if ($forwardfor) {

            $parts = explode(',', $forwardfor);
            return $parts[0];
        }
        $realip = isset($_SERVER['HTTP_X_REAL_IP']) ? $_SERVER['HTTP_X_REAL_IP'] : null;
        if ($realip) {
            return $realip;
        }
        return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null;
    }

}
