<?php

namespace AppBundle\Form;

use AppBundle\Entity\Client;
use AppBundle\Entity\Order;
use AppBundle\Entity\OrderDetail;
use AppBundle\Entity\ReportDetail;
use AppBundle\Repository\ClientRepositoryInterface;
use AppBundle\Repository\OrderRepositoryInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;

class ReportType extends AbstractType
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
        ])
        ->add('details', CollectionType::class, [
            'entry_type' => ReportDetailType::class,
            'allow_add' => false,
            'allow_delete' => false
        ])->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();
            $details = $data->getDetails();
            $error = true;
            /** @var ReportDetail $detail */
            foreach ($details as $detail) {
                if (!is_null($detail->getQuantity())) {
                    $error = false;
                    break;
                }
            }
            if ($error) {
                $form->get('details')->addError(new FormError('You should report at least one product.'));
            }

        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Report'
        ]);
    }

    public function getBlockPrefix()
    {
        return 'report';
    }


}
