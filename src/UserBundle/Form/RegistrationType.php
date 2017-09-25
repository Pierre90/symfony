<?php
/**
 * Created by PhpStorm.
 * User: pierre
 * Date: 9/19/17
 * Time: 11:55 AM
 */

namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('firstName', null, array('label' => 'Prénom'))->add('lastName', null, array('label' => 'Nom'));
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';

    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }


}