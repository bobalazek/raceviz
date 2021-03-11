<?php

namespace App\Admin\Field;

use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

final class RaceResultStatusField implements FieldInterface
{
    use FieldTrait;

    public static function new(string $propertyName, ?string $label = null): self
    {
        return (new self())
            ->setProperty($propertyName)
            ->setLabel($label)
            ->setFormType(ChoiceType::class)
            ->setFormTypeOption('choices', [
                'Finished (F)' => 'f',
                'DNF (Did Not Finish)' => 'dnf',
                'DSQ (Disqualified)' => 'dsq',
                'DNS (Did Not Start)' => 'dns',
                'Excluded (X)' => 'x',
            ])
        ;
    }
}
