<?php

namespace ADW\SliderBundle\Admin;


use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

/**
 * Class RelatedCityAdmin
 * Project adw/slider-bundle
 * @package ADW\SliderBundle\Admin
 * @author Anton Prokhorov
 */
class RelatedCityAdmin extends AbstractAdmin
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
//                    'show' => array(),
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