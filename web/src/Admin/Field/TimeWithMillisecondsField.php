<?php

namespace App\Admin\Field;

use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;
use Symfony\Component\Form\Extension\Core\Type\TimeType;

final class TimeWithMillisecondsField implements FieldInterface
{
    use FieldTrait;

    public const OPTION_WIDGET = 'widget';

    public static function new(string $propertyName, ?string $label = null): self
    {
        return (new self())
            ->setProperty($propertyName)
            ->setLabel($label)
            ->setFormType(TimeType::class)
            ->setFormTypeOption('attr', ['step' => 0.001])
            ->setTemplatePath('contents/admin/fields/time_with_milliseconds.html.twig')
            ->addCssClass('field-time-with-milliseconds')
            ->setHelp('This MUST be the following format: "minutes:seconds.miliseconds" (1:40.950)')
        ;
    }

    public function renderAsNativeWidget(bool $asNative = true): self
    {
        if (false === $asNative) {
            $this->renderAsChoice();
        } else {
            $this->setCustomOption(self::OPTION_WIDGET, DateTimeField::WIDGET_NATIVE);
        }

        return $this;
    }

    public function renderAsChoice(bool $asChoice = true): self
    {
        if (false === $asChoice) {
            $this->renderAsNativeWidget();
        } else {
            $this->setCustomOption(self::OPTION_WIDGET, DateTimeField::WIDGET_CHOICE);
        }

        return $this;
    }

    public function renderAsText(bool $asText = true): self
    {
        if (false === $asText) {
            $this->renderAsNativeWidget();
        } else {
            $this->setCustomOption(self::OPTION_WIDGET, DateTimeField::WIDGET_TEXT);
        }

        return $this;
    }
}
