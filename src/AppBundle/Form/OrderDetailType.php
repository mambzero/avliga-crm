<?php

namespace AppBundle\Form;

use AppBundle\Entity\Product;
use AppBundle\Repository\ProductRepositoryInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

class OrderDetailType extends AbstractType
{

    private $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $products = $this->productRepository->listActive();

        $builder
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
            ])
            ->add('quantity', IntegerType::class, [
                'constraints' => [
                    new NotBlank(),
                    new GreaterThan(0)
                ],
                'label' => false,
                'required' => false
            ])
            ->add('discount', NumberType::class, [
                'constraints' => [
                    new NotBlank(),
                    new GreaterThanOrEqual(0)
                ],
                'label' => false,
                'required' => false
            ])
            ->add('price', NumberType::class, [
                'constraints' => [
                    new NotBlank(),
                    new GreaterThanOrEqual(0)
                ],
                'label' => false,
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\OrderDetail'
        ));
    }

    public function getBlockPrefix()
    {
        return 'detail';
    }


}
