<?php


namespace App\App\Support\AddressClassificator\Provider;

use App\Support\AddressClassificator\Pipe\SuggestionDTO;
use Dadata\DadataClient;

/**
 * Class DaDataProvider
 * @package App\App\Support\AddressClassificator\Provider
 */
class DaDataDataProvider implements DataProviderInterface
{
    protected DadataClient $dadata;

    public function __construct()
    {
        $config = config('dadata');
        $this->dadata = new DadataClient($config['token'], $config['secret']);
    }

    /**
     * @param string $query
     * @param array $params
     * @return SuggestionDTO[]
     */
    public function request(string $query, array $params = []): array
    {
        $additionalParams = array_filter($params, static function ($k) {
            return $k !== 'query';
        }, ARRAY_FILTER_USE_KEY);
        $suggestions = $this->dadata->suggest('address', $query, 5, $additionalParams);

        return array_map(static function ($el) {
            return SuggestionDTO::fromDadataArray($el);
        }, $suggestions);
    }
}
