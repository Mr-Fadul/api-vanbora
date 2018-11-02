<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;


class AdType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description')
            ->add('short_description')
            ->add('category', 'entity', array(
                'label' => 'Dúvida',
                'placeholder' => 'Defina a categoria',
                'class' => 'AppBundle\Entity\Category',
                'required' => false
            ))
            ->add('photo', 'hidden', array())
            ->add('photoTemp',
                'file' ,
                array(
                    'label' => 'Imagem',
                    'required' => false
                )
            );
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Ad'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ad';
    }
}
