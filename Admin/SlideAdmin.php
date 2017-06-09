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
use Symfony\Component\Form\Extension\Core\Type\RadioType;

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
            ->add('name');
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('name')
            ->add('status')
            ->add('type')
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

        $context = $this->container->getParameter('adw_slider.media_context');

        $formMapper
            ->add('name', null, [
                'attr' => [
                    'class' => 'name'
                ]
            ])
            ->add('status', 'choice', [
                'choices' => [
                    Slide::STATUS_SHOW => 'Показывается',
                    Slide::STATUS_TIME => 'Временный показ',
                    Slide::STATUS_HIDE => 'Скрыт'
                ],
                'attr' => [
                  'class' => 'status'
                ],
                'expanded' => true,
                'multiple' => false,
                'label' => 'Тип'
            ])
            ->add('type', 'choice', [
                'choices' => [
                    Slide::TYPE_IMG => 'Картинка со ссылкой',
                    Slide::TYPE_TEXT => 'HTML-код'
                ],
                'expanded' => true,
                'multiple' => false,
                'label' => 'Тип слайда'
            ])
            ->add('media', 'sonata_media_type', [
                    'label' => 'Изображение',
                    'provider' => 'sonata.media.provider.image',
                    'context' => $context,
                    'required' => false
                ]
            )
            ->add('url', null, [
                'label' => 'URL',
                'attr' => [
                    'class' => ''
                ]
            ])
            ->add('text', null, [
//            ->add('text', 'hidden', [
                'label' => 'HTML-код',
                'attr' => [
                    'class' => ''
                ]
            ])
            ->add('sort', 'hidden', [
                'attr' => [
                    'class' => ''
                ]
            ])
            ->add('is_user', null , [
                'label' => 'Только для авторизованных пользователей'
            ])
            ->add('delay', null, [
                'label' => 'Интервал',
                'attr' => [
                    'class' => ''
                ]
            ])
            ->add('publication_start_date', 'sonata_type_datetime_picker', [
//                'format' => 'YYYY-MM-DD HH:MM:SS',
                'required' => false,
                'attr' => [
                    'class' => 'sonata_type_datetime_picker'
                ]
            ])
            ->add('publication_end_date', 'sonata_type_datetime_picker', [
//                'format' => 'YYYY-MM-DD HH:MM:SS',
                'required' => false,
                'attr' => [
                    'class' => 'sonata_type_datetime_picker'
                ]
            ])
            ->end()
        ;
    }


    /**
     * @inheritdoc
     */
    public function toString($object)
    {
        return $object ? $object->getName() : 'Slide';
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
