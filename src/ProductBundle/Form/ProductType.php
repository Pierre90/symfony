<?php

namespace ProductBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\EmbedItemForm;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

class ProductType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title')->add('description')->add('price')->add('image')->add('category', Select2EntityType::class, [
            'multiple' => false,
            'remote_route' => 'category_search',
            'class' => 'ProductBundle\Entity\Category',
            'primary_key' => 'id',
            'text_property' => 'name',
            'minimum_input_length' => 1,
            'page_limit' => 10,
            'allow_clear' => true,
            'delay' => 250,
            'cache' => true,
            'cache_timeout' => 60000, // if 'cache' is true
            'language' => 'fr',
            'placeholder' => 'Select a category',
            // 'object_manager' => $objectManager, // inject a custom object / entity manager
        ]);

      /*  $builder->add('category',  CategoryType::class, array(
            'required' => FALSE,
            'mapped' => FALSE,
            'property_path' => 'category',
        ));

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function(FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();

            if (!empty($data['newcategory']['name'])) {
                $form->remove('category');

                $form->add('category',  CategoryType::class, array(
                    'required' => TRUE,
                    'mapped' => TRUE,
                    'property_path' => 'category',
                ));
            }
        });*/
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ProductBundle\Entity\Product'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'productbundle_product';
    }


}
