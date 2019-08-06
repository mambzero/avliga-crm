<?php

namespace AppBundle\Form;

use AppBundle\Entity\Client;
use AppBundle\Entity\Product;
use AppBundle\Repository\ClientRepositoryInterface;
use AppBundle\Repository\ProductRepositoryInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;

class ReEntryType extends AbstractType
{

    private $clientRepository;
    private $productRepository;

    public function __construct(ClientRepositoryInterface $clientRepository, ProductRepositoryInterface $productRepository)
    {
        $this->clientRepository = $clientRepository;
        $this->productRepository = $productRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $clients = $this->clientRepository->listActive();
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
            ]);
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
