<?php

namespace AppBundle\Form;

use AppBundle\Entity\Client;
use AppBundle\Entity\ReportDetail;
use AppBundle\Repository\ReportDetailRepositoryInterface;
use AppBundle\Service\ClientServiceInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;

class ReportType extends AbstractType
{
    private $clientService;
    private $reportDetailRepository;

    public function __construct(ClientServiceInterface $clientService, ReportDetailRepositoryInterface $reportDetailRepository)
    {
        $this->clientService = $clientService;
        $this->reportDetailRepository = $reportDetailRepository;

    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $clients = $this->clientService->listAll(true);

        $builder->add('dateAdded', DateType::class, [
            'widget' => 'single_text',

            // prevents rendering it as type="date", to avoid HTML5 date pickers
            'html5' => false,

            'constraints' => [
                new NotBlank(),
            ],
            'required' => false
        ]);

        $builder->add('client', EntityType::class, [
            'class' => Client::class,
            'choices' => $clients,
            'choice_label' => 'name',
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
                /**
                 * @var ReportDetail[] $details
                 * @var Client $client
                 */
                $data = $event->getData();
                $form = $event->getForm();
                $details = $data->getDetails();
                $client = $data->getClient();
                $error = true;

                foreach ($details as $detail) {
                    if (!is_null($detail->getQuantity())) {
                        $error = false;
                        break;
                    }
                }

                if ($error) {
                    $form->get('details')->addError(new FormError('You should report at least one product.'));
                    return;
                }

                if (!is_null($client)) {
                    foreach ($details as $key => $detail) {
                        $quantity = $detail->getQuantity();
                        if (!is_null($quantity)) {
                            $clientStock = $this->clientService->getClientStockByProduct($client,$detail->getProduct());
                            if (!is_null($data->getId()) && !is_null($detail->getId())) {
                                $quantity -= $this->reportDetailRepository->getQuantity($detail->getId());
                            }
                            if (is_null($clientStock) && $quantity > 0) {
                                $form->get('details')
                                    ->get($key)
                                    ->get('product')
                                    ->addError(new FormError('No stocks in client.'));
                            } elseif ($clientStock < $quantity && $quantity > 0) {
                                $form->get('details')
                                    ->get($key)
                                    ->get('quantity')
                                    ->addError(new FormError('Not enough stocks in client.'));
                            }
                        }
                    }
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
