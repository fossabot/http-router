<?php declare(strict_types=1);

/**
 * It's free open-source software released under the MIT License.
 *
 * @author Anatoly Fenric <anatoly@fenric.ru>
 * @copyright Copyright (c) 2018, Anatoly Fenric
 * @license https://github.com/sunrise-php/http-router/blob/master/LICENSE
 * @link https://github.com/sunrise-php/http-router
 */

namespace Sunrise\Http\Router\Loader;

/**
 * Import classes
 */
use Sunrise\Http\Router\Exception\InvalidLoaderResourceException;
use Sunrise\Http\Router\RouteCollectionInterface;

/**
 * LoaderInterface
 */
interface LoaderInterface
{

    /**
     * Attaches the given resource to the loader
     *
     * @param mixed $resource
     *
     * @return void
     *
     * @throws InvalidLoaderResourceException
     */
    public function attach($resource) : void;

    /**
     * Loads routes from attached resources
     *
     * @return RouteCollectionInterface
     *
     * @throws \RuntimeException May be thrown if any error occurred.
     */
    public function load() : RouteCollectionInterface;
}
