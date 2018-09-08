<?php

namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('email')
            ->add('celphone')
            ->add('phone')
            ->add('address')
            ->add('city')
            ->add('uf', 'choice', array(
                        'choices' => array('' => 'UF',
                            'AC' => 'AC',
                            'AL' => 'AL',
                            'AP' => 'AP',
                            'AM' => 'AM',
                            'BA' => 'BA',
                            'CE' => 'CE',
                            'DF' => 'DF',
                            'ES' => 'ES',
                            'GO' => 'GO',
                            'MA' => 'MA',
                            'MT' => 'MT',
                            'MS' => 'MS',
                            'MG' => 'MG',
                            'PR' => 'PR',
                            'PB' => 'PB',
                            'PA' => 'PA',
                            'PE' => 'PE',
                            'PI' => 'PI',
                            'RJ' => 'RJ',
                            'RN' => 'RN',
                            'RS' => 'RS',
                            'RO' => 'RO',
                            'RR' => 'RR',
                            'SC' => 'SC',
                            'SE' => 'SE',
                            'SP' => 'SP',
                            'TO' => 'TO',),
                            'required' => false,))
            ->add('plainPassword', 'password', array())
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'UserBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'userbundle_user';
    }
}
