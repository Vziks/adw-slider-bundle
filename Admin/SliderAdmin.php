<?php

namespace ADW\SliderBundle\Admin;

use ADW\SliderBundle\Entity\Slider;
use ADW\SliderBundle\Form\Type\PositionedSlideMediaCollectionType;
use ADW\SliderBundle\Form\Type\PositionedSlideMediaType;
use ADW\SliderBundle\Traits\AdminServiceContainerTrait;
use Doctrine\ORM\QueryBuilder;
use phpDocumentor\Reflection\Types\Integer;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\Filter\NumberType;
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


    public function createQuery($context = 'list')
    {
        {
            /** @var QueryBuilder $query */
            $query = parent::createQuery($context);

//            $query
//                ->select('o')
//                ->addSelect('count(sl.id) AS HIDDEN r')
//                ->join($query->getRootAlias() . '.slides', 'sl')
////                ->addSelect('count(sl.id)')
//            ->groupBy($query->getRootAlias() . '.id')
//            ;
////                ->addSelect($query->getRootAlias() . '.name' )
////                $query->add('select', 'm', false )
////            ;
//
//            dump($query->getQuery()->getSQL());
//            dump($query->getRootAlias());

//            die;
            return $query;
        }
    }


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
            ->addIdentifier('name')
            ->add('height', null, array(
                'sortable' => true,
                'template' => 'ADWSliderBundle:Admin/CRUD:list_format_slider.html.twig',
                    'label' => 'Формат'))
            ->add('countSlide', null, array('associated_tostring' => 'getCountSlide','label' => 'Слайдов'))
            ->add('is_show', null, array(
                'editable' => true
            ))
//            ->add('CountSlide', 'doctrine_orm_callback', array(
//                'callback' => function ($queryBuilder, $alias, $field, $value) {
//
//                    dump($value);
//                    die;
//
//                    if (!$value || !isset($value['value']) || !isset($value['value']['type']) || !$value['value']['type']) {
//                        return;
//                    }
//
//                    $operators = array(
//                        NumberType::TYPE_EQUAL => '=',
//                        NumberType::TYPE_GREATER_EQUAL => '>=',
//                        NumberType::TYPE_GREATER_THAN => '>',
//                        NumberType::TYPE_LESS_EQUAL => '<=',
//                        NumberType::TYPE_LESS_THAN => '<',
//                    );
//
//                    $operator = $operators[$value['value']['type']];
//
//                    $queryBuilder->andWhere('SIZE(' . $alias . '.comments) ' . $operator . ' :size');
//                    $queryBuilder->setParameter('size', $value['value']['value']);
//                },
//                'field_type' => 'sonata_type_filter_number',
//            ))
//            ->add('CountSlide', null, array(
//                'show_filter' => true
//            ))
//            ->add('CountSlide', null, array(
//                'sortable' => 'CountSlide',
//            ))

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

        $id = $this->getSubject()->getId();

        $arrayAttr = ['attr' => [
            'readonly' => true
        ]];

        $formMapper
            ->with('Publish Workflow', ['tab' => false])
            ->add('id', 'hidden', [])
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
                'label' => 'Описание баннера (не показывается на сайте)',
                'required' => true
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
