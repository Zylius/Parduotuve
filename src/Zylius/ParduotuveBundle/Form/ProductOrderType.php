<?php

namespace Zylius\ParduotuveBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProductOrderType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date')
            ->add('user')
            ->add('confirmer')
            ->add('products', null, ['property' => 'name'])
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Zylius\ParduotuveBundle\Entity\ProductOrder'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'zylius_parduotuvebundle_productorder';
    }
}
