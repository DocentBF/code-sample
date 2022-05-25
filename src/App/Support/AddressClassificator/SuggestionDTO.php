<?php


namespace App\Support\AddressClassificator\Pipe;

use App\Support\DTO\DTO;

/**
 * Class SuggestionDTO
 * @package App\Support\AddressClassificator
 */
class SuggestionDTO extends DTO
{
    /**
     * @var string
     */
    public string $address = '';
    /**
     * @var float
     */
    public float $latitude;
    /**
     * @var float
     */
    public float $longitude;

    /**
     * SuggestionDTO constructor.
     * @param array $parameters
     */
    public function __construct(array $parameters = [])
    {
        parent::__construct($parameters);
    }

    /**
     * @param array $data
     * @return SuggestionDTO
     */
    public static function fromDadataArray(array $data): SuggestionDTO
    {
        $dtoData = [
            'address'   => $data['value'],
            'latitude'  => $data['geo_lat'],
            'longitude' => $data['geo_lon'],
        ];

        return new static($dtoData);
    }

}
