<?php

namespace AppBundle\Form;

use AppBundle\Repository\RoleRepositoryInterface;
use AppBundle\Repository\UserRepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{

    private $roleRepository;
    private $userRepository;

    public function __construct(RoleRepositoryInterface $roleRepository, UserRepositoryInterface $userRepository)
    {
        $this->roleRepository = $roleRepository;
        $this->userRepository = $userRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $roles = $this->roleRepository->getRoles();

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
            ->add('roles', ChoiceType::class,[
                'data_class' => null,
                'choices' => $roles,
                'choice_label' => 'name',
                'choice_value' => function ($choice) {
                    if (is_string($choice)) {
                        $choice = $this->roleRepository->findByName($choice);
                    }
                    return $choice->getId();
                },
                'multiple' => true,
                'constraints' => [
                    new NotBlank(),
                    new Choice(['choices' => $roles, 'multiple' => true])
                ],
                'label' => false,
                'required' => false,
            ])->addEventListener(
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
            'data_class' => 'AppBundle\Entity\User'
        ));
    }

    public function getBlockPrefix()
    {
        return 'user';
    }

    public function passwordValidation(FormEvent $event)
    {
        $user = $event->getData();
        $form = $event->getForm();

        if (is_null($user->getId())) {
            $form->add('password', RepeatedType::class,[
                'type' => PasswordType::class,
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => 'Confirm'],
                'invalid_message' => 'Password mismatch.',
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 4, 'max' => 255])
                ],
                'label' => false,
                'required' => false
            ]);
        }

    }

    public function checkEmail(FormEvent $event)
    {

        $user = $event->getData();
        $form = $event->getForm();
        $email = $user->getEmail();

        if ($email === null) {
            return;
        }

        $emailFromDB = $this->userRepository->findByEmail($email);

        if (
            (!is_null($emailFromDB) && is_null($user->getId())) ||
            (!is_null($emailFromDB) && !is_null($user->getId()) && $user->getEmail() !== $this->userRepository->getEmailById($user->getId())) ) {
            $form->get('email')->addError(new FormError('This email is already taken.'));
        }
    }
}