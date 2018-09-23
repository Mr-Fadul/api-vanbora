<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class DocumentDriverType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cnhImage', 'hidden', array())
            ->add('cnhImageTemp', FileType::class, array(
                    'label' => 'Foto da CNH',
                    'required' => true
            ))
            ->add('crlvImage', 'hidden', array())
            ->add('crlvImageTemp', FileType::class, array(
                    'label' => 'Foto da CRLV',
                    'required' => true
            ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\DocumentDriver'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'document_driver';
    }
}
