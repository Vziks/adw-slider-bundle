<?php

namespace ADW\SliderBundle\Repository;

use ADW\SliderBundle\Entity\City;
use ADW\SliderBundle\Entity\RelatedCity;
use ADW\SliderBundle\Traits\AdminServiceContainerTrait;
use ADW\SliderBundle\Traits\RepositoryTokenStorageTrait;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

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

        $user = $this->getUser();

        $userIp = $this->getUserIp();

        $em = $this->getEntityManager();

        $cityRelated = NULL;

        $userLocation = $this->container->get('adw.geoip.handler')->getLocation($userIp);




        if ($userLocation && $userLocation->getCode() == 'RU') {

            $userCity = $this->container->get('adw.geoip.handler')->getCity('185.22.181.170');

            $relatedCity = $em->getRepository('ADWSliderBundle:RelatedCity')->findOneBy(['prefix' => 'is_' . strtolower($userLocation->getCode()) . '_' . strtolower($this->translit($userCity))]);
            if (!$relatedCity && $userCity) {
                $relatedCity = new RelatedCity();
                $relatedCity->setName($userCity);
                $relatedCity->setPrefix('is_' . strtolower($userLocation->getCode()) . '_' . strtolower($this->translit($userCity)));


                $em->persist($relatedCity);
                $em->flush();
            }

            $userLocationArray = $userLocation->toArray();

            /**
             * @var City $cityRelated
             */
            $cityRelated = $em->getRepository('ADWSliderBundle:RelatedCity')->findOneBy(['prefix' => 'is_' . strtolower($userLocation->getCode()) . '_' . strtolower($this->translit($userLocationArray['city']['name']))]);

        }

        $criterias = [];

        $qb
            ->select('s', 'sl', 'ct')
            ->join('s.slides', 'sl')
            ->leftJoin('sl.citys', 'ct')
            ->where($qb->expr()->eq('s.sysName', ':sName'));

        $criterias[] = $qb->expr()->andx(
            $qb->expr()->eq('sl.status', ':showstatus'),
            $qb->expr()->eq('sl.is_user', 0),
            $qb->expr()->isNull('ct.prefix')
        );

        if($user) {
            $criterias[] = $qb->expr()->andx(
                $qb->expr()->eq('sl.status', ':showstatus'),
                $qb->expr()->eq('sl.is_user', 1),
                $qb->expr()->isNull('ct.prefix')
            );
        }

        if ($cityRelated && $cityRelated->getCity()) {

            $criterias[] = $qb->expr()->andx(
                $qb->expr()->eq('sl.status', ':showstatus'),
                $qb->expr()->eq('sl.is_user', 0),
                $qb->expr()->eq('ct.prefix', ':city')
            );

            if($user) {
                $criterias[] = $qb->expr()->andx(
                    $qb->expr()->eq('sl.status', ':showstatus'),
                    $qb->expr()->eq('sl.is_user', 1),
                    $qb->expr()->eq('ct.prefix', ':city')
                );
            }
        }

        $criterias[] = $qb->expr()->andx(
            $qb->expr()->eq('sl.status', ':timestatus'),
            $qb->expr()->lte('sl.publicationStartDate', ':cdate'),
            $qb->expr()->gte('sl.publicationEndDate', ':cdate'),
            $qb->expr()->eq('sl.is_user', 0),
            $qb->expr()->isNull('ct.prefix')
        );
        if($user) {
            $criterias[] = $qb->expr()->andx(
                $qb->expr()->eq('sl.status', ':timestatus'),
                $qb->expr()->lte('sl.publicationStartDate', ':cdate'),
                $qb->expr()->gte('sl.publicationEndDate', ':cdate'),
                $qb->expr()->eq('sl.is_user', 1),
                $qb->expr()->isNull('ct.prefix')
            );
        }

        if ($cityRelated && $cityRelated->getCity()) {

            $criterias[] = $qb->expr()->andx(
                $qb->expr()->eq('sl.status', ':timestatus'),
                $qb->expr()->lte('sl.publicationStartDate', ':cdate'),
                $qb->expr()->gte('sl.publicationEndDate', ':cdate'),
                $qb->expr()->eq('sl.is_user', 0),
                $qb->expr()->eq('ct.prefix', ':city')
            );

            if($user) {
                $criterias[] = $qb->expr()->andx(
                    $qb->expr()->eq('sl.status', ':timestatus'),
                    $qb->expr()->lte('sl.publicationStartDate', ':cdate'),
                    $qb->expr()->gte('sl.publicationEndDate', ':cdate'),
                    $qb->expr()->eq('sl.is_user', 1),
                    $qb->expr()->eq('ct.prefix', ':city')
                );
            }
        }

        $where = \call_user_func_array([$qb->expr(), "orx"], $criterias);

        $qb->andWhere($where);

        $qb->setParameter('showstatus', 'show');
        $qb->setParameter('timestatus', 'time');

        if ($cityRelated && $cityRelated->getCity()) {
            $qb->setParameter('city', $cityRelated->getCity()->getPrefix());
        }

        $qb->setParameter('cdate', new \DateTime());
        $qb->setParameter('sName', $sysName);

        $q = $qb->getQuery()->getOneOrNullResult();

        return $q;

    }

    protected function getUserIp()
    {

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


    public function getRandomIp()
    {
        $ip = [];
        while (count($ip) < 4) {
            $ip[] = rand(0, 255);
        }
        return join('.', $ip);
    }

    public function getRandomIps($count = 1000)
    {
        $ips = array();
        while (count($ips) < $count) {
            $ips[] = $this->getRandomIp();
        }
        return $ips;
    }

    public function transliterate($st)
    {
        $st = strtr($st,
            "абвгдежзийклмнопрстуфыэАБВГДЕЖЗИЙКЛМНОПРСТУФЫЭ",
            "abvgdegziyklmnoprstufieABVGDEGZIYKLMNOPRSTUFIE"
        );
        $st = strtr($st, array(
            'ё' => "yo", 'х' => "h", 'ц' => "ts", 'ч' => "ch", 'ш' => "sh",
            'щ' => "shch", 'ъ' => '', 'ь' => '', 'ю' => "yu", 'я' => "ya",
            'Ё' => "Yo", 'Х' => "H", 'Ц' => "Ts", 'Ч' => "Ch", 'Ш' => "Sh",
            'Щ' => "Shch", 'Ъ' => '', 'Ь' => '', 'Ю' => "Yu", 'Я' => "Ya",
        ));
        return $st;
    }

    public function translit($s)
    {
        $s = (string)$s; // преобразуем в строковое значение
        $s = strip_tags($s); // убираем HTML-теги
        $s = str_replace(array("\n", "\r"), " ", $s); // убираем перевод каретки
        $s = preg_replace("/\s+/", ' ', $s); // удаляем повторяющие пробелы
        $s = trim($s); // убираем пробелы в начале и конце строки
        $s = function_exists('mb_strtolower') ? mb_strtolower($s, 'UTF-8') : strtolower($s); // переводим строку в нижний регистр (иногда надо задать локаль)
        $s = strtr($s, array('а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'e', 'ж' => 'j', 'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'shch', 'ы' => 'y', 'э' => 'e', 'ю' => 'yu', 'я' => 'ya', 'ъ' => '', 'ь' => ''));
        $s = preg_replace("/[^0-9a-z-_ ]/i", "", $s); // очищаем строку от недопустимых символов
        $s = str_replace(" ", "-", $s); // заменяем пробелы знаком минус
        return $s; // возвращаем результат
    }


    public function getUser()
    {
        if (!$this->container->has('security.token_storage')) {
            throw new \LogicException('The SecurityBundle is not registered in your application.');
        }

        if (null === $token = $this->tokenStorage->getToken()) {
            return;
        }

        if (!is_object($user = $token->getUser())) {
            // e.g. anonymous authentication
            return;
        }

        return $user;
    }

}
