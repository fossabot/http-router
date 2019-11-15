<?php declare(strict_types=1);

/**
 * It's free open-source software released under the MIT License.
 *
 * @author Anatoly Fenric <anatoly@fenric.ru>
 * @copyright Copyright (c) 2018, Anatoly Fenric
 * @license https://github.com/sunrise-php/http-router/blob/master/LICENSE
 * @link https://github.com/sunrise-php/http-router
 */

namespace Sunrise\Http\Router\Annotation;

/**
 * Import classes
 */
use Psr\Http\Server\MiddlewareInterface;
use InvalidArgumentException;

/**
 * Import functions
 */
use function class_exists;
use function is_array;
use function is_int;
use function is_string;
use function is_subclass_of;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
final class Route
{

    /**
     * @var object
     */
    public $source;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $path;

    /**
     * @var array
     */
    public $methods;

    /**
     * @var array
     */
    public $middlewares;

    /**
     * @var array
     */
    public $attributes;

    /**
     * @var int
     */
    public $priority;

    /**
     * @param array $params
     */
    public function __construct(array $params)
    {
        $this->assertValidName($params);
        $this->assertValidPath($params);
        $this->assertValidMethods($params);
        $this->assertValidMiddlewares($params);
        $this->assertValidAttributes($params);
        $this->assertValidPriority($params);

        $this->name = $params['name'];
        $this->path = $params['path'];
        $this->methods = $params['methods'];
        $this->middlewares = $params['middlewares'] ?? [];
        $this->attributes = $params['attributes'] ?? [];
        $this->priority = $params['priority'] ?? 0;
    }

    /**
     * @param array $params
     * @return void
     * @throws InvalidArgumentException
     */
    private function assertValidName(array $params) : void
    {
        if (empty($params['name']) || !is_string($params['name'])) {
            throw new InvalidArgumentException('@Route.name must be not an empty string.');
        }
    }

    /**
     * @param array $params
     * @return void
     * @throws InvalidArgumentException
     */
    private function assertValidPath(array $params) : void
    {
        if (empty($params['path']) || !is_string($params['path'])) {
            throw new InvalidArgumentException('@Route.path must be not an empty string.');
        }
    }

    /**
     * @param array $params
     * @return void
     * @throws InvalidArgumentException
     */
    private function assertValidMethods(array $params) : void
    {
        if (empty($params['methods']) || !is_array($params['methods'])) {
            throw new InvalidArgumentException('@Route.methods must be not an empty array.');
        }

        foreach ($params['methods'] as $method) {
            if (!is_string($method)) {
                throw new InvalidArgumentException('@Route.methods must contain only strings.');
            }
        }
    }

    /**
     * @param array $params
     * @return void
     * @throws InvalidArgumentException
     */
    private function assertValidMiddlewares(array $params) : void
    {
        if (!isset($params['middlewares'])) {
            return;
        } elseif (!is_array($params['middlewares'])) {
            throw new InvalidArgumentException('@Route.middlewares must be an array.');
        }

        foreach ($params['middlewares'] as $middleware) {
            if (!is_string($middleware)) {
                throw new InvalidArgumentException('@Route.middlewares must contain only strings.');
            } elseif (!class_exists($middleware)) {
                throw new InvalidArgumentException('@Route.middlewares contains nonexistent class.');
            } elseif (!is_subclass_of($middleware, MiddlewareInterface::class)) {
                throw new InvalidArgumentException('@Route.middlewares contains non middleware class.');
            }
        }
    }

    /**
     * @param array $params
     * @return void
     * @throws InvalidArgumentException
     */
    private function assertValidAttributes(array $params) : void
    {
        if (!isset($params['attributes'])) {
            return;
        } elseif (!is_array($params['attributes'])) {
            throw new InvalidArgumentException('@Route.attributes must be an array.');
        }
    }

    /**
     * @param array $params
     * @return void
     * @throws InvalidArgumentException
     */
    private function assertValidPriority(array $params) : void
    {
        if (isset($params['priority']) && !is_int($params['priority'])) {
            throw new InvalidArgumentException('@Route.priority must be an integer.');
        }
    }
}
