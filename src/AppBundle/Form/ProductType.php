<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class ProductType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->getOptions('title'))
            ->add('price', NumberType::class, $this->getOptions('price'))
            ->add('isbn', TextType::class, $this->getOptions('isbn'))
            ->add('type', ChoiceType::class, $this->getOptions('type'))
            ->add('image', FileType::class, $this->getOptions('image'))
            ->add('active', CheckboxType::class,$this->getOptions('active'))
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $product = $event->getData();
            $form = $event->getForm();
            if (!$product || null === $product->getId()) {
                $form->add('image', FileType::class, $this->getOptions('image', true));
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Product'
        ));
    }

    public function getBlockPrefix()
    {
        return 'product';
    }

    /**
     * Returns field options.
     *
     * @param $field
     * @param bool $isNewRecord
     * @return array
     */
    private function getOptions($field, $isNewRecord = false): array
    {

        $options = [];

        $notBlank = ucfirst($field).' should not be blank.';

        switch ($field) {
            case "title":
            case "isbn":
                $options['constraints'] = [
                    new NotBlank(['message' => $notBlank]),
                    new Length(['min' => 3, 'max' => 255])
                ];
                break;
            case "type":
                $options['choices'] = [
                    'Book' => 1,
                    'E-book' => 2
                ];
                $options['constraints'] = [
                    new Choice([
                        'choices' => [1, 2]
                    ])
                ];
                break;
            case "price":
                $options['constraints'] = [
                    new NotNull(['message' => $notBlank]),
                    new GreaterThanOrEqual(0)
                ];
                break;
            case "image":
                $options['data'] = null;
                $options['constraints'][] = new Image();
                if ($isNewRecord) {
                    $options['constraints'][] = new NotNull(['message' => 'Upload image file.']);
                }
                break;
            case "active":
                $options['required'] = false;
                break;
        }

        return $options;
    }

}
