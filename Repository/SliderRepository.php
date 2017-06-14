<?php

namespace ADW\SliderBundle\Repository;

use ADW\SliderBundle\Traits\AdminServiceContainerTrait;
use ADW\SliderBundle\Traits\RepositoryTokenStorageTrait;
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

        $qb = $this->createQueryBuilder('s');

        $usr = $this->tokenStorage->getToken()->getUser();

//        $userIp = $this->getUserIp();
//
//
//        if ($userIp == '127.0.0.1') {
//
//            $testIp = [
//                '185.22.181.170',
//                '79.142.82.62'
//            ];
//
//            $userIp = $testIp[mt_rand(0, count($testIp) - 1)];
//        }
//
//        $randoms = $this->getRandomIps(25);
//
//        foreach ($randoms as $random) {
//
//                    $userGeoBaze = $this->container->get('adw.geoip.provider.geobaze')->findLocationByIp($random);
//
//                    $userIpGeoBaze = $this->container->get('adw.geoip.provider.ipgeobaze')->findLocationByIp($random);
//
//            if($userGeoBaze && $userIpGeoBaze) {
//
//                dump($random);
//
//                dump($userGeoBaze->toArray());
//
//                dump($userIpGeoBaze->toArray());
//;
//                dump('~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~');
//            }
//
//
//        }
//
//        die;


        $criterias = [];

        $qb
            ->select('s', 'sl')
            ->join('s.slides', 'sl')
            ->where($qb->expr()->eq('s.sysName', ':sName'));

        $criterias[] = $qb->expr()->andx(
            $qb->expr()->neq('sl.status', ':hidestatus'),
            $qb->expr()->eq('sl.status', ':showstatus'),
            ($usr ? $qb->expr()->eq('sl.is_user', 0): $qb->expr()->eq('sl.is_user', 1))
        );

        $criterias[] = $qb->expr()->andx(
            $qb->expr()->neq('sl.status', ':hidestatus'),
            $qb->expr()->eq('sl.status', ':timestatus'),
            $qb->expr()->lte('sl.publicationStartDate', ':cdate'),
            $qb->expr()->gte('sl.publicationEndDate', ':cdate'),
            ($usr ? $qb->expr()->eq('sl.is_user', 0): $qb->expr()->eq('sl.is_user', 1))
        );

        $where = \call_user_func_array([$qb->expr(), "orx"], $criterias);

        $qb->andWhere($where);

        $qb->setParameter('hidestatus', 'hide');
        $qb->setParameter('showstatus', 'show');
        $qb->setParameter('timestatus', 'time');
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


    public function getRandomIp() {
        $ip = [];
        while (count($ip) < 4) {
            $ip[] = rand(0, 255);
        }
        return join('.', $ip);
    }

    public function getRandomIps($count=1000) {
        $ips = array();
        while (count($ips) < $count) {
            $ips[] = $this->getRandomIp();
        }
        return $ips;
    }

}
