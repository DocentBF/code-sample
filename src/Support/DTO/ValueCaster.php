<?php


namespace App\Support\DTO;

use Spatie\DataTransferObject\DataTransferObject;
use Spatie\DataTransferObject\FieldValidator;
use Spatie\DataTransferObject\ValueCaster as DefaultValueCaster;

class ValueCaster extends DefaultValueCaster
{
    const CASTABLE_TYPES = [
        'boolean', 'bool',
        'integer', 'int',
        'float', 'double',
        'string',
        'array',
        'object',
        'null'
    ];

    /**
     * @param $value
     * @param FieldValidator $validator
     * @return array|mixed|DataTransferObject
     */
    public function cast($value, FieldValidator $validator)
    {
        if (is_array($value))
            return parent::cast($value, $validator);

        if ($validator->isValidType($value))
            return $value;

        return $this->castValue($value, $validator->allowedTypes);
    }

    /**
     * @param $value
     * @param array $allowedTypes
     * @return bool|mixed|DataTransferObject
     */
    public function castValue($value, array $allowedTypes)
    {
        $castTo = null;

        foreach ($allowedTypes as $type) {
            $castTo = $type;

            break;
        }

        if (! $castTo) {
            return $value;
        }

        if (is_subclass_of($type, DataTransferObject::class)) {
            return new $castTo($value);
        } elseif ($this->isCastable($type)) {
            settype($value, $type);
            return $value;
        } else {
            return $value;
        }
    }

    /**
     * @param $type
     * @return bool
     */
    public function isCastable($type): bool
    {
        return array_search($type, static::CASTABLE_TYPES) !== false;
    }
}
