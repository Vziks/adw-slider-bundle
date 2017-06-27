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
            ->add('sysName');
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

        $id = $this->getSubject()->getId();

        $arrayAttr = ['attr' => [
            'readonly' => true,
            'disabled' => true
        ]];

        $formMapper
            ->with('Publish Workflow', ['tab' => false])
            ->add('name', null, [
                'label' => 'Название баннера'
            ])
            ->add('sysName')
            ->add(
                $formMapper->create('_banner-width', 'form', array('label' => 'Формат баннера', 'virtual' => true))
                    ->add('width', null, ($id && $this->getSubject() instanceof Slider ? $arrayAttr : []))
                    ->add('height', null, ($id && $this->getSubject() instanceof Slider ? $arrayAttr : []))
            )
            ->add('description', null, [
                'label' => 'Описание баннера (не показывается на сайте)'
            ])
            ->add('is_show')
            ->add('slides',
                'sonata_type_collection',
                [
                    'label' => 'Слайды',
                    'required' => false,
                    'by_reference' => false,
                    'btn_add' => 'Добавить слайд',

                ],
                [
                    'edit' => 'inline',
                    'allow_delete' => true,
                    'sortable' => 'sort'
                ]
            )
            ->end()
            ->end();

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
