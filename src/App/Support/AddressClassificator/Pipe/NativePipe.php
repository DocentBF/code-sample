<?php

namespace App\Support\AddressClassificator\Pipe;

use App\App\Support\AddressClassificator\Provider\DataProviderInterface;
use App\App\System\Container;

/**
 * Class NativePipe
 * @package App\Support\AddrClassificator
 */
class NativePipe extends CLADRFactory implements PipeInterface
{
    /** @var DataProviderInterface */
    protected $dataSource;

    /**
     * CLADRNative constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        parent::__construct($config);
        $this->dataSource = Container::resolve(DataProviderInterface::class);
    }

    /**
     * Get suggestions
     *
     * @param string $query
     * @param array $additionalParams
     * @return SuggestionDTO[]
     */
    public function suggestions(string $query, array $additionalParams = []): array
    {
        return $this->dataSource->request($query, $additionalParams);
    }
}
