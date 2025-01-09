<?php

namespace App\Enums;

enum CustomFieldTypes: string
{
    private const TEXT = 'Text';

    private const NUMERIC = 'Numeric';

    private const DECIMAL = 'Decimal';

    private const NUMBER = 'Number';

    private const DATE = 'Date';

    private const DATETIME = 'DateTime';

    private const BOOLEAN = 'Boolean';

    public static function options(): array
    {
        return collect(self::values())->map(fn ($value) => [
            'value' => $value->getValue(),
            'label' => $value->getLabel(),
        ])->all();
    }

    public function getLabel(): string
    {
        return match ($this->getValue()) {
            self::TEXT => 'Text',
            self::NUMERIC => 'Numeric',
            self::DECIMAL => 'Decimal',
            self::NUMBER => 'Number',
            self::DATE => 'Date',
            self::DATETIME => 'DateTime',
            self::BOOLEAN => 'Boolean',
        };
    }
}
