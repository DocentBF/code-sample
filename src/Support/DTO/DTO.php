<?php


namespace App\Support\DTO;

use Spatie\DataTransferObject\DataTransferObject;

/**
 * Class DTO
 * + Primitive types auto-casting
 *
 * @package App\Support\DTO
 */
abstract class DTO extends DataTransferObject
{
    /**
     * @param \Spatie\DataTransferObject\ValueCaster $valueCaster
     * @param \Spatie\DataTransferObject\FieldValidator $fieldValidator
     * @param mixed $value
     *
     * @return mixed
     */
    protected function castValue(\Spatie\DataTransferObject\ValueCaster $valueCaster, \Spatie\DataTransferObject\FieldValidator $fieldValidator, $value)
    {
        return $valueCaster->cast($value, $fieldValidator);
    }

    /**
     * @return \Spatie\DataTransferObject\ValueCaster
     */
    protected function getValueCaster(): \Spatie\DataTransferObject\ValueCaster
    {
        return new ValueCaster();
    }

    /**
     * @return false|string
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }

    /**
     * @param $json
     * @return static
     */
    public static function fromJson($json)
    {
        return new static(json_decode($json, true));
    }
}
