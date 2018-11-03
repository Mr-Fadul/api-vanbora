<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class AdType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('short_description')
            ->add('price')
            ->add('monthly')
            ->add('payment_online')
            ->add('category', 'entity', array(
                'label' => 'Categoria',
                'placeholder' => 'Defina a categoria',
                'class' => 'AppBundle\Entity\Category',
                'required' => false
            ))
            ->add('where_i_spend', CollectionType::class, array(
                'entry_type' => WhereISpendType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ))
            ->add('photo', CollectionType::class, array(
                'entry_type' => PhotoAdType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ))
            ->add('photo_highlight', 'hidden', array())
            ->add('photo_highlight_temp', FileType::class, array(
                    'label' => 'Foto destaque',
                    'required' => true
            ));
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
