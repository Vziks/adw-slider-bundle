<?php

namespace ADW\SliderBundle\Command;

use ADW\GeoIpBundle\Entity\IpGeoBaseLocation;
use ADW\SliderBundle\Entity\RelatedCity;
use ADW\SliderBundle\Repository\SliderRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;
/**
 * Class UserRelatedCityCommand.
 * Project contest-fake-backend.
 * @author Anton Prokhorov
 */
class UserRelatedCityCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this
            ->setName("adw:slider:urcity:create")
            ->setDescription("Create geo ip base")
            ->setHelp("Create ip geobase from file");
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return boolean
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {

        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $this->truncateTable(RelatedCity::class, $em);

        $qb = $em->getRepository('ADWGeoIpBundle:IpGeoBaseLocation')->createQueryBuilder('ip');

        /**
         * @var IpGeoBaseLocation[] $ipGeoBaseLocations
         */
        $ipGeoBaseLocations = $qb
            ->where($qb->expr()->eq('ip.code', ':code'))
            ->andWhere($qb->expr()->isNotNull('ip.city'))
            ->orderBy('ip.city')
            ->groupBy('ip.city')
            ->setParameter('code', 'RU')
            ->getQuery()
            ->getResult();
        ;

        $progress = new ProgressBar($output, count($ipGeoBaseLocations));
        $progress->setFormatDefinition('custom', ' [%bar%] %elapsed%/%estimated% %current% из %max% %message%');
        $progress->setFormat('custom');
        $progress->setMessage('записей обработано');

        foreach ($ipGeoBaseLocations as $ipGeoBaseLocation){

            $relatedCity = new RelatedCity();

            $relatedCity->setName($ipGeoBaseLocation->getCity());
            $relatedCity->setPrefix('is_' . strtolower($ipGeoBaseLocation->getCode()) . '_' . strtolower(SliderRepository::translit($ipGeoBaseLocation->getCity())));
            $em->persist($relatedCity);

            $progress->advance();
        }

        $em->flush();

        $progress->finish();
        $output->writeln('');

        return false;

    }

    /**
     * @param $entity
     * @param EntityManager $em
     * @return bool
     */
    protected function truncateTable($entity, EntityManager $em)
    {
        $table = $em->getClassMetadata($entity)->getTableName();

        $sql = "DELETE FROM $table";
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();

        $sql = "SET FOREIGN_KEY_CHECKS = 0;";
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();

        $sql = "TRUNCATE TABLE $table";
        $stmt = $em->getConnection()->prepare($sql);

        return $stmt->execute();
    }

}