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
use Symfony\Component\Form\Extension\Core\Type\DateType;

class ProductType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', null,array('label' => 'Titre'))
            ->add('description', null,array('label' => 'Description'))
            ->add('image', null,array('label' => 'Image'))
            ->add('category', Select2EntityType::class, [
            'multiple' => false,
            'remote_route' => 'category_search',
            'class' => 'ProductBundle\Entity\Category',
            'primary_key' => 'id',
            'text_property' => 'name',
            'minimum_input_length' => 0,
            'page_limit' => 10,
            'allow_clear' => true,
            'delay' => 250,
            'cache' => true,
            'cache_timeout' => 60000, // if 'cache' is true
            'language' => 'fr',
            'placeholder' => 'Choisir une categorie...',
            'label' => 'Catégorie',
            'allow_add' => [
                'enabled' => true,
                'new_tag_text' => ' (Créer)',
                'new_tag_prefix' => '__',
                'tag_separators' => ''
            ],
        ])
        ->add('startingPrice', null,array('label' => 'Prix de départ'))
            ->add('minimumBid', null,array('label' => 'Enchère minimum'))
            ->add('maximumBid', null,array('label' => 'Prix d\'achat immédiat', 'required'=>false))
            ->add('endDate', DateType::class,array('label' => 'Date fin des enchères', 'required'=>false, 'placeholder' => array(
                'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour'),'format' => 'dd-MM-yyyy'))
        ;

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
