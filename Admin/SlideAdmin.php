<?php

namespace ADW\SliderBundle\Admin;

use ADW\SliderBundle\Entity\Slide;
use ADW\SliderBundle\Entity\Slider;
use ADW\SliderBundle\Traits\AdminServiceContainerTrait;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Model\Metadata;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * Class SliderAdmin
 * Project adw/slider-bundle
 * @package ADW\SliderBundle\Admin
 * @author Anton Prokhorov
 */
class SlideAdmin extends AbstractAdmin
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

        $context = $this->container->getParameter( 'adw_slider.media_context' );

        $formMapper
            ->add('name')
            ->add('media', 'sonata_media_type', array(
                    'label'    => 'Изображение',
                    'provider' => 'sonata.media.provider.image',
                    'context'  => $context,
                    'required' => false
                )
            )
            ->add('sort', 'hidden')
            ->add('url')
            ->add('text')
            ->add('delay')
            ->add('status', ChoiceType::class, [
                'choices' => [
                    Slide::STATUS_SHOW => 'Показывать',
                    Slide::STATUS_TIME => 'Показывать по времени',
                    Slide::STATUS_HIDE => 'Скрыть'
                ]
            ])
            ->add('publication_start_date', 'sonata_type_datetime_picker', [
            ])
            ->add('publication_end_date', 'sonata_type_datetime_picker', [
            ])
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

}
