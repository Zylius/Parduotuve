<?php

namespace Zylius\ParduotuveBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Zylius\ParduotuveBundle\Entity\User;

class UserType extends AbstractType
{
    /** @var  User */
    private $user;

    public function __construct(User $caller)
    {
        $this->user = $caller;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('email');

        if (in_array('ROLE_SUPER_ADMIN', $this->user->getRoles())) {
            $builder->add('roles', 'choice',
                [
                    'choices' => [
                        'ROLE_SUPER_ADMIN' => 'user.roles.administrator',
                        'ROLE_ADMIN' => 'user.roles.manager',
                        'ROLE_USER' => 'user.roles.user',
                    ],
                    'multiple' => true,
                    'expanded' => true,
                ]
            );
        }
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Zylius\ParduotuveBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'zylius_parduotuvebundle_user';
    }
}
