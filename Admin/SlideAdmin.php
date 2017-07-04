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

//        dump($this->getSubject());
//        die;

        $formMapper
            ->with('1')
            ->add('id', 'hidden', [])
            ->add('slider.id', 'hidden', [
                'mapped' => false,
                'data' => ($this->getSubject() ? $this->getSubject()->getSlider()->getId() : null )
            ])
            ->add('type', 'choice', [
                'choices' => [
                    Slide::TYPE_IMG => 'Картинка и ссылка',
                    Slide::TYPE_TEXT => 'HTML-код'
                ],
                'expanded' => true,
                'multiple' => false,
                'label' => 'Тип слайда'
            ])
            ->add('media', 'sonata_media_type', [
                    'label' => false,
                    'provider' => 'sonata.media.provider.image',
                    'context' => $context,
                    'required' => false
                ]
            )
            ->add('url', null, [
                'label' => 'URL:',
                'attr' => [
                    'class' => ''
                ]
            ])
            ->add('text', null, [
                'attr' => [
                    'class' => ''
                ]
            ])
            ->end()
            ->add('sort', 'hidden', [
                'attr' => [
                    'class' => ''
                ]
            ])
            ->end()
            ->with('2')
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
                'label' => 'Показ в баннере'
            ])
            ->add('is_user', null , [
                'label' => 'Только для авторизованных пользователей'
            ])
            ->add('citys', 'sonata_type_model_autocomplete', [
                'property' => "name",
                'multiple' => true,
                'placeholder' => 'Показывать везде',
                'label' => 'География'
            ])
            ->add('publication_start_date', 'sonata_type_datetime_picker', [
//                'format' => 'YYYY-MM-DD HH:MM:SS',
                'required' => false,
                'attr' => [
                    'class' => 'sonata_type_datetime_picker'
                ],
                'label' => 'Даты и время показа по Мск'
            ])
            ->add('publication_end_date', 'sonata_type_datetime_picker', [
//                'format' => 'YYYY-MM-DD HH:MM:SS',
                'required' => false,
                'attr' => [
                    'class' => 'sonata_type_datetime_picker'
                ],
                'label' => ''
            ])
            ->add('delay', null, [
                'label' => 'Интервал (сек)',
                'attr' => [
                    'class' => ''
                ]
            ])
            ->add('name', null, [
                'attr' => [
                    'class' => 'name'
                ],
                'label' => 'Название'
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
