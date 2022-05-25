<?php


namespace App\App\Support\AddressClassificator\Provider;


use App\Support\AddressClassificator\Pipe\SuggestionDTO;

/**
 * Interface SourceInterface
 * @package App\App\Support\AddressClassificator\Provider
 */
interface DataProviderInterface
{
    /**
     * @param string $query
     * @param array $params
     * @return SuggestionDTO[]
     */
    public function request(string $query, array $params = []): array;
}
