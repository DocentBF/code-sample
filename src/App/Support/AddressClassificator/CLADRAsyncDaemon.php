<?php

namespace App\Support\AddressClassificator\Pipe;

use App\App\Support\AddressClassificator\Provider\DataProviderInterface;
use App\App\System\Container;
use App\System\Daemon\AsyncServerDaemon;
use App\System\Daemon\DaemonInterface;
use App\System\Log;
use Psr\Http\Message\ServerRequestInterface;
use React\EventLoop\LoopInterface;
use React\Http\Message\Response;
use React\Http\Server;
use Throwable;

/**
 * Class CLADRAsyncDaemon
 * @package App\Support\AddrClassificator
 */
class CLADRAsyncDaemon extends AsyncServerDaemon implements DaemonInterface
{
    /** @var string|null host */
    protected ?string $host;
    /** @var int|null port */
    protected ?int $port;
    /** @var Server */
    protected Server $server;
    /** @var LoopInterface */
    protected $loop;
    /** @var DataProviderInterface */
    protected $dataProvider;
    /** @var bool */
    protected bool $verbose;

    /**
     * CLADRAsyncDaemon constructor.
     * @param string|null $host
     * @param int|null $port
     * @param bool $verbose
     */
    public function __construct(?string $host = null, ?int $port = null, bool $verbose = false)
    {
        $this->verbose = $verbose;

        parent::__construct($host, $port);
    }

    /**
     * @return void
     */
    public function run(): void
    {
        parent::run();
    }

    /**
     * @param ServerRequestInterface $request
     * @return Response
     */
    public function requestHandler(ServerRequestInterface $request): Response
    {
        $params = $request->getQueryParams();
        $query = $params['query'] ?? null;

        try {
            $suggestions = $this->dataProvider->request($query, $params);

            if ($this->verbose)
                Log::info(var_export($suggestions));

            array_walk($suggestions, function(&$el) {
                /** @var SuggestionDTO $el */
                $el = $el->toJson();
            });

        } catch (Throwable $e) {
            Log::error('Error requesting dadata.ru: ' . $e->getMessage());
            $suggestions = [];
        }

        return new Response(
            200,
            [
                'Content-Type' => 'application/json',
            ],
            json_encode($suggestions)
        );
    }

    /**
     * Set initial values
     *
     * @return mixed|void
     */
    public function initialize(): void
    {
        $config = config('cladr.async');

        if (is_null($this->host)) {
            $this->host = $config['host'];
        }

        if (is_null($this->port)) {
            $this->port = $config['port'];
        }

        $this->dataProvider = Container::resolve($config['dataSource']);
    }
}
