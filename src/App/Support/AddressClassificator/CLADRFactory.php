<?php


namespace App\Support\AddressClassificator\Pipe;

use Exception;
use InvalidArgumentException;

/**
 * Class CLADR
 * Address classificator
 *
 * @package AddrClassificator
 */
abstract class CLADRFactory
{
    /** @var array */
    public static array $instances = [];
    /** @var string */
    protected static string $default = AsyncPipe::class;
    /** @var array */
    protected array $config;

    /**
     * CLADR constructor.
     * @param array $config
     */
    protected function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @param string|null $type
     * @return PipeInterface
     * @throws InvalidArgumentException
     */
    public static function factory(?string $type = null): PipeInterface
    {
        if ($type === null) {
            $type = self::$default;
        }

        if (isset(self::$instances[$type])) {
            return self::$instances[$type];
        }

        $config = config('cladr');

        if (!isset($config[$type])) {
            throw new InvalidArgumentException("Failed to load config for CLADR type: {$type}");
        }

        $config = $config[$type];
        self::$instances[$type] = new $type($config);

        return self::$instances[$type];
    }

    /**
     * @throws Exception
     */
    final public function __clone()
    {
        throw new Exception('Cloning of CLADR objects is forbidden');
    }

    /**
     * Get suggestions
     *
     * @param string $query
     * @param array $additionalParams
     * @return mixed
     */
    abstract public function suggestions(string $query, array $additionalParams = []);

}
