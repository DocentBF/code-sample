<?php

namespace App\Support\AddressClassificator\Pipe;

use App\System\Log;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use Throwable;

/**
 * Class AsyncPipe
 * @package App\Support\AddrClassificator
 */
class AsyncPipe extends CLADRFactory implements PipeInterface
{
    const ASYNC_PIPE_TIMEOUT = 10;
    const ASYNC_PIPE_CONNECT_TIMEOUT = 10;

    /**
     * AsyncPipe constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        parent::__construct($config);
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
        $url = 'http://' . $this->config['host'] . ':' . $this->config['port'];

        $bigQuery = array_replace(['query' => $query], $additionalParams);
        $client = new Client();

        $suggestions = [];
        try {
            $response = $client->request('GET', $url,
                [
                    'timeout'         => $config['timeout'] ?? self::ASYNC_PIPE_TIMEOUT,
                    'connect_timeout' => $config['connect_timeout'] ?? self::ASYNC_PIPE_CONNECT_TIMEOUT,
                    'query'           => $bigQuery,
                ]
            );
            if ($response->getStatusCode() !== 200)
                throw new Exception('Error requesting cladr async: ' . $response->getStatusCode() . ' ' . $response->getBody());

            $suggestions = $response->getBody()->getContents();
            $suggestions = json_decode($suggestions, true);

            return array_map(static function ($el) {
                return SuggestionDTO::fromJson($el);
            }, $suggestions);
        } catch (ConnectException $e) {
            $cladr = CLADRFactory::factory(NativePipe::class);
            $suggestions = $cladr->suggestions($query, $additionalParams);

            Log::error('Cladr Async error: ' . $e->getMessage() . "\n" . $e->getCode());
        } catch (Throwable $e) {
            Log::error('Cladr Async error: ' . $e->getMessage() . "\n" . $e->getCode());
        }

        return $suggestions;
    }
}
