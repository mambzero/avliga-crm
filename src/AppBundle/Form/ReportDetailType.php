<?php


namespace AppBundle\Form;

use AppBundle\Entity\ReportDetail;
use AppBundle\Form\DataTransformer\ProductTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

class ReportDetailType extends AbstractType
{
    private $transformer;

    public function __construct(ProductTransformer $productTransformer)
    {
        $this->transformer = $productTransformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('product', HiddenType::class)
            ->add('quantity', IntegerType::class,[
                'required' => false,
                'label' => false
            ])
            ->add('discount', NumberType::class, [
                'constraints' => [
                    new NotBlank(),
                    new GreaterThanOrEqual(0)
                ],
                'required' => false,
                'label' => false,
                'scale' => 2
            ])
            ->add('price', NumberType::class, [
                'constraints' => [
                    new NotBlank(),
                    new GreaterThanOrEqual(0)
                ],
                'required' => false,
                'label' => false,
                'scale' => 2
            ]);

        $builder->get('product')->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ReportDetail::class
        ]);
    }

    public function getBlockPrefix()
    {
        return 'report_detail';
    }

}