<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class ClientType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('company', TextType::class, $this->getOptions('company'))
            ->add('address', TextType::class, $this->getOptions('address'))
            ->add('responsiblePerson', TextType::class, $this->getOptions('responsiblePerson'))
            ->add('uniqueIdentifier', TextType::class, $this->getOptions('uniqueIdentifier'))
            ->add('vat', CheckboxType::class,$this->getOptions('vat'))
            ->add('discount', NumberType::class, $this->getOptions('discount'));

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Client'
        ));
    }

    public function getBlockPrefix()
    {
        return 'client';
    }

    /**
     * Returns field options.
     *
     * @param $field
     * @return array
     */
    private function getOptions($field): array
    {

        $options = [];

        $notBlank = ucfirst($field).' should not be blank.';

        switch ($field) {
            case "company":
            case "address":
            case "responsiblePerson":
            case "uniqueIdentifier":
                $options['constraints'] = [
                    new NotBlank(['message' => $notBlank]),
                    new Length(['min' => 3, 'max' => 255])
                ];
                break;
            case "discount":
                $options['constraints'] = [
                    new NotNull(['message' => $notBlank]),
                    new GreaterThanOrEqual(0)
                ];
                break;
            case "vat":
                $options['required'] = false;
                break;
        }

        return $options;
    }

}
