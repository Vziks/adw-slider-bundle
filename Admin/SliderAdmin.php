<?php

namespace ADW\SliderBundle\Admin;

use ADW\SliderBundle\Entity\Slider;
use ADW\SliderBundle\Form\Type\PositionedSlideMediaCollectionType;
use ADW\SliderBundle\Form\Type\PositionedSlideMediaType;
use ADW\SliderBundle\Traits\AdminServiceContainerTrait;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Model\Metadata;
use Sonata\AdminBundle\Route\RouteCollection;

/**
 * Class SliderAdmin
 * Project adw/slider-bundle
 * @package ADW\SliderBundle\Admin
 * @author Anton Prokhorov
 */
class SliderAdmin extends AbstractAdmin
{

    use AdminServiceContainerTrait;

    /**
     * @var bool
     */
    public $supportsPreviewMode = true;


    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('name')
            ->add('sysName')
            ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('name')
            ->add('sysName')
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
            ->with('Publish Workflow', ['tab' => false])
            ->add('name')
            ->add('sysName')
            ->add('description')
            ->end()
            ->with('Publish Workflow')
            ->add('slides',
                'sonata_type_collection',
                [
                    'label'        => 'Слайды',
                    'required'     => false,
                    'by_reference' => false,
                    'btn_add'      => 'Добавить слайд',

                ],
                [
                    'edit'         => 'inline',
//                    'edit'         => 'table',
                    'allow_delete' => true,
                    'sortable' => 'sort'
                ]
            )
            ->end()
            ->end()
        ;

    }


    /**
     * @inheritdoc
     */
    public function toString($object)
    {
        return $object ? $object->getName() : 'Slider';
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

    public function getFormTheme()
    {
        return array_merge(
            parent::getFormTheme(),
            array('ADWSliderBundle:Admin/CRUD:form_admin_fields.html.twig')
        );
    }


}
