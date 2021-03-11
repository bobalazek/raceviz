<?php

namespace App\Admin\Field;

use App\Form\Type\TimeDurationType;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;

final class TimeWithMillisecondsField implements FieldInterface
{
    use FieldTrait;

    public const OPTION_FORMAT_WITH_HOURS = 'format_with_hours';

    public static function new(string $propertyName, ?string $label = null): self
    {
        return (new self())
            ->setProperty($propertyName)
            ->setLabel($label)
            ->setFormType(TimeDurationType::class)
            ->setTemplatePath('contents/admin/fields/time_with_milliseconds.html.twig')
            ->addCssClass('field-time-with-milliseconds')
            ->setCustomOption(self::OPTION_FORMAT_WITH_HOURS, false)
        ;
    }

    /**
     * Will format the output with hours.
     */
    public function setFormatWithHours(): self
    {
        $this->setCustomOption(self::OPTION_FORMAT_WITH_HOURS, true);

        return $this;
    }
}
