<?php declare(strict_types=1);

/**
 * It's free open-source software released under the MIT License.
 *
 * @author Anatoly Fenric <anatoly@fenric.ru>
 * @copyright Copyright (c) 2018, Anatoly Fenric
 * @license https://github.com/sunrise-php/http-router/blob/master/LICENSE
 * @link https://github.com/sunrise-php/http-router
 */

namespace Sunrise\Http\Router\OpenApi;

/**
 * Import functions
 */
use function array_walk_recursive;
use function in_array;

/**
 * AbstractObject
 */
abstract class AbstractObject implements ObjectInterface
{

    /**
     * The fields specified in this array will not be returned when converting this object to an array
     *
     * @var string[]
     */
    protected const IGNORE_FIELDS = [];

    /**
     * The fields specified in this array will be renamed when converting this object to an array
     *
     * The constant must contain the following structure: `[field => alias, ...]`
     *
     * @var array
     */
    protected const FIELD_ALIASES = [];

    /**
     * Recursively converts the object into an array and its children
     *
     * {@inheritDoc}
     */
    public function toArray() : array
    {
        $fields = $this->getFields();

        array_walk_recursive($fields, function (&$value) {
            if ($value instanceof ObjectInterface) {
                $value = $value->toArray();
            }
        });

        return $fields;
    }

    /**
     * Gets all filled fields from the object
     *
     * @return array
     */
    public function getFields() : array
    {
        $fields = [];

        foreach ($this as $name => $value) {
            // empty field...
            if (null === $value) {
                continue;
            }

            // ignored field...
            if (in_array($name, static::IGNORE_FIELDS)) {
                continue;
            }

            // the field has an alias. renaming...
            if (isset(static::FIELD_ALIASES[$name])) {
                $name = static::FIELD_ALIASES[$name];
            }

            $fields[$name] = $value;
        }

        return $fields;
    }
}
