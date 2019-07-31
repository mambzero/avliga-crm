<?php

namespace AppBundle\Form;

use AppBundle\Entity\Client;
use AppBundle\Repository\ClientRepositoryInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;

class OrderType extends AbstractType
{

    private $clientRepository;

    public function __construct(ClientRepositoryInterface $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $clients = $this->clientRepository->listActive();

        $builder->add('client', EntityType::class, [
            'class' => Client::class,
            'choices' => $clients,
            'choice_label' => 'company',
            'choice_value' => 'id',
            'choice_attr' => function (Client $client, $key, $index) {
                return ['data-discount' => $client->getDiscount()];
            },
            'placeholder' => 'Select client',
            'constraints' => [
                new NotBlank(),
                new Choice(['choices' => $clients])
            ],
            'required' => false
        ]);

        $builder->add('details', CollectionType::class, [
            'entry_type' => OrderDetailType::class,
            'entry_options' => ['label' => false],
            'allow_add' => true,
            'allow_delete' => true
        ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Order'
        ));
    }

    public function getBlockPrefix()
    {
        return 'order';
    }

}
