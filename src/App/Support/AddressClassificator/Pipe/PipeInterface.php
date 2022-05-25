<?php


namespace App\Support\AddressClassificator\Pipe;

/**
 * Interface PipeInterface
 * @package Support\AddrClassificator
 */
interface PipeInterface
{
    /**
     * Get suggestions by query
     *
     * @param string $query
     * @param array $additionalParams
     * @return SuggestionDTO[]
     */
    public function suggestions(string $query, array $additionalParams = []): array;
}
