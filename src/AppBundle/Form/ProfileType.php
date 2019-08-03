<?php


namespace AppBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class ProfileType extends UserType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('email', EmailType::class,[
                'constraints' => [
                    new NotBlank(),
                    new Email(),
                    new Length(['max' => 255])
                ],
                'label' => false,
                'required' => false,
            ])
            ->add('password', RepeatedType::class,[
                'type' => PasswordType::class,
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => 'Confirm'],
                'invalid_message' => 'Password mismatch.',
                'constraints' => [
                    new Length(['min' => 4, 'max' => 255])
                ],
                'label' => false,
                'required' => false
            ])
            ->addEventListener(
                FormEvents::PRE_SET_DATA,
                [$this, 'passwordValidation']
            )->addEventListener(
                FormEvents::POST_SUBMIT,
                [$this, 'checkEmail']
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Profile'
        ));
    }

    public function getBlockPrefix()
    {
        return 'profile';
    }
}