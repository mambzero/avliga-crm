<?php

namespace AppBundle\Form;

use AppBundle\Entity\Client;
use AppBundle\Entity\Product;
use AppBundle\Repository\ProductRepositoryInterface;
use AppBundle\Repository\ReturnRepositoryInterface;
use AppBundle\Service\ClientServiceInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;

class ReEntryType extends AbstractType
{

    private $clientService;
    private $productRepository;
    private $returnRepository;

    public function __construct(ClientServiceInterface $clientService, ProductRepositoryInterface $productRepository, ReturnRepositoryInterface $returnRepository)
    {
        $this->clientService = $clientService;
        $this->productRepository = $productRepository;
        $this->returnRepository = $returnRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $clients = $this->clientService->listAll(true);
        $products = $this->productRepository->listActive();

        $builder
            ->add('client', EntityType::class, [
                'class' => Client::class,
                'choices' => $clients,
                'choice_label' => 'company',
                'choice_value' => 'id',
                'placeholder' => 'Select client',
                'constraints' => [
                    new NotBlank(),
                    new Choice(['choices' => $clients])
                ],
                'required' => false
            ])
            ->add('product', EntityType::class,[
                'class' => Product::class,
                'choices' => $products,
                'choice_label' => 'title',
                'choice_value' => 'id',
                'choice_attr' => function (Product $product, $key, $index) {
                    return ['data-price' => $product->getPrice()];
                },
                'placeholder' => 'Select product',
                'constraints' => [
                    new NotBlank(),
                    new Choice(['choices' => $products])
                ],
                'label' => false,
                'required' => false,
            ])->add('quantity', IntegerType::class, [
                'constraints' => [
                    new NotBlank(),
                    new GreaterThan(0)
                ],
                'label' => false,
                'required' => false
            ])->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {

                $data = $event->getData();
                $form = $event->getForm();
                $client = $data->getClient();
                $product = $data->getProduct();

                if (!is_null($client) && !is_null($product)) {
                    $stock = $this->clientService->getClientStockByProduct($client,$product);
                    $quantity = $data->getQuantity();

                    if (!is_null($data->getId())) {
                        $quantity -= $this->returnRepository->getQuantity($data->getId());
                    }

                    if (is_null($stock) && $quantity > 0) {
                        $form->get('product')->addError(new FormError('No stocks in client.'));
                    } elseif ($stock < $quantity && $quantity > 0) {
                        $form->get('quantity')->addError(new FormError('Not enough stocks is client.'));
                    }

                }

            });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\ReEntry'
        ));
    }

    public function getBlockPrefix()
    {
        return 'return';
    }

}
