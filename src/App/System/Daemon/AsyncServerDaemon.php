<?php


namespace App\System\Daemon;


use App\System\Log;
use Psr\Http\Message\ServerRequestInterface;
use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;
use React\Http\Message\Response;
use React\Socket\Server;
use Throwable;

/**
 * Class AsyncServerDaemon
 * @package App\System\Daemon
 */
abstract class AsyncServerDaemon extends AbstractDaemon
{
    /** @var string|null host */
    protected ?string $host;
    /** @var int|null port */
    protected ?int $port;
    /** @var \React\Http\Server */
    protected \React\Http\Server $server;
    /** @var LoopInterface */
    protected $loop;

    /**
     * AsyncServerDaemon constructor.
     *
     * @param string|null $host
     * @param int|null $port
     */
    public function __construct(?string $host, ?int $port)
    {
        $this->host = $host;
        $this->port = $port;

        $this->initialize();
    }

    /**
     * Init method
     */
    abstract public function initialize(): void;

    /**
     * @param ServerRequestInterface $request
     * @return Response
     */
    public function requestHandler(ServerRequestInterface $request): Response
    {
        return new Response(
            200,
            [
                'Content-Type' => 'application/json',
            ],
            'Hello World!\n'
        );
    }

    /**
     * Start server
     */
    public function run(): void
    {
        parent::run();
        try {
            $this->startServer();
        } catch (Throwable $e) {
            Log::critical("Start server exception: " . $e->getMessage());
            $this->stop();
        }
    }

    /**
     * Run server
     */
    protected function startServer()
    {
        $this->loop = Factory::create();

        $this->server = new \React\Http\Server($this->loop, [$this, 'requestHandler']);
        $socket = new Server($this->host . ":" . $this->port, $this->loop);
        $this->server->listen($socket);

        Log::info("Starting server at http://$this->host:$this->port");

        $this->loop->run();
    }

    /**
     * Stop server
     *
     * @return void
     */
    public function stop(): void
    {
        parent::stop();
    }

    /**
     * Reload daemon
     *
     * @return void
     */
    public function reload(): void
    {
        parent::reload();
    }

    /**
     *
     */
    protected function halt(): void
    {
        $this->loop->stop();
    }
}
