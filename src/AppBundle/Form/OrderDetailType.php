<?php

namespace AppBundle\Form;

use AppBundle\Entity\Product;
use AppBundle\Repository\OrderDetailRepositoryInterface;
use AppBundle\Repository\ProductRepositoryInterface;
use AppBundle\Repository\WarehouseRepositoryInterface;
use AppBundle\Service\WarehouseServiceInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

class OrderDetailType extends AbstractType
{

    private $productRepository;
    private $warehouseRepository;
    private $detailRepository;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        WarehouseRepositoryInterface $warehouseRepository,
        OrderDetailRepositoryInterface $orderDetailRepository
    )
    {
        $this->productRepository = $productRepository;
        $this->warehouseRepository = $warehouseRepository;
        $this->detailRepository = $orderDetailRepository;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $products = $this->productRepository->listActive(Product::BOOK);

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
                'required' => false,
                'scale' => 2
            ])
            ->add('price', NumberType::class, [
                'constraints' => [
                    new NotBlank(),
                    new GreaterThanOrEqual(0)
                ],
                'label' => false,
                'required' => false,
                'scale' => 2
            ])->addEventListener(
                FormEvents::POST_SUBMIT,
                [$this, 'checkQuantity']
            );
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

    public function checkQuantity(FormEvent $event)
    {
        /** @var $product Product */
        $detail = $event->getData();
        $form = $event->getForm();

        $quantity = $detail->getQuantity();
        $product = $detail->getProduct();

        if (!is_null($detail->getId())) {
            $quantity-=$this->detailRepository->getQuantity($detail->getId());
        }

        if ($product) {
            $stock = $this->warehouseRepository->getStock($product->getId());
            if ($stock < $quantity) {
                $form->get('quantity')->addError(new FormError('Not enough quantity!'));
            }
        }
    }

}
