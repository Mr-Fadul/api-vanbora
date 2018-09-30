<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CouponType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('promotionalCode', TextType::class)
            ->add('quantity')
            ->add('expirationDate', 'date', array(
                'empty_value' => '',
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'attr' => array('class'=>'form-control date-picker'),
                'required' => true
            ))
            ->add('typeValue', ChoiceType::class, array(
                    'choices' => array(
                        'Monetário' => 'Monetário',
                        'Porcentagem' => 'Porcentagem'
                    ),
                    'required'    => true))
            ->add('monetaryValue', TextType::class, array(
                    'required' => false
                )
            )
            ->add('percentValue', TextType::class, array(
                    'required' => false
                )
            )
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Coupon'
        ));
    }
}
