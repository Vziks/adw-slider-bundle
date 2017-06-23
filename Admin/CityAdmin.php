<?php

namespace ADW\SliderBundle\Admin;

use Doctrine\ORM\QueryBuilder;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Model\Metadata;
use Sonata\AdminBundle\Route\RouteCollection;

/**
 * Class CityAdmin
 * Project adw/slider-bundle
 * @package ADW\SliderBundle\Admin
 * @author Anton Prokhorov
 */
class CityAdmin extends AbstractAdmin
{

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('name')
            ->add('prefix');
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('name')
            ->add('prefix')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'edit' => array(),
                    'delete' => array(),
                ),
            ));
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name')
            ->add('prefix')
            ->add('relatedCitys', 'sonata_type_model_autocomplete', [
                'property' => "name",
                'multiple' => true,
                'required' => true,
                'by_reference' => false,
                'label' => 'Связанные города',
//                'callback' => function ($admin, $property, $value) {
//                    $datagrid = $admin->getDatagrid();
//                    /**
//                     * @var QueryBuilder $queryBuilder
//                     */
//                    $queryBuilder = $datagrid->getQuery();
//                    $queryBuilder
//                        ->where( $queryBuilder->expr()->isNull($queryBuilder->getRootAliases()[0] . '.city'));
//                    $datagrid->setValue($property, null, $value);
//                },
//                'to_string_callback' => function($entity, $property) {
//                    return sprintf('ID: %s, Город %s, Префикс (%s)', $entity->getId(), $entity->getName(), $entity->getPrefix());
//                }

            ])
            ->end();
    }

    /**
     * @inheritdoc
     */
    public function toString($object)
    {
        return $object ? $object->getName() : 'Город';
    }


    public function getFilterParameters()
    {
        $this->datagridValues = array_merge(array(
            '_sort_order' => 'DESC',
            '_sort_by' => 'sort'
        ),
            $this->datagridValues

        );
        return parent::getFilterParameters();
    }

}