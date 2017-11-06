<?php

declare(strict_types=1);

/**
 * Hoa
 *
 *
 * @license
 *
 * New BSD License
 *
 * Copyright © 2007-2017, Hoa community. All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of the Hoa nor the names of its contributors may be
 *       used to endorse or promote products derived from this software without
 *       specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDERS AND CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 */

namespace Hoa\Stream\Filter;

use Hoa\Consistency;
use Hoa\Stream;

/**
 * Class \Hoa\Stream\Filter.
 *
 * Proposes some methods to handle filter.
 */
abstract class Filter extends Stream
{
    /**
     * Overwrite filter if already exists.
     */
    public const OVERWRITE        = true;

    /**
     * Do not overwrite filter if already exists.
     */
    public const DO_NOT_OVERWRITE = false;

    /**
     * Filter should only be applied when reading.
     */
    public const READ             = STREAM_FILTER_READ;

    /**
     * Filter should only be applied when writing.
     */
    public const WRITE            = STREAM_FILTER_WRITE;

    /**
     * Filter should be applied when reading and writing.
     */
    public const READ_AND_WRITE   = STREAM_FILTER_ALL;

    /**
     * All resources with at least one filter registered.
     */
    protected static $_resources = [];



    /**
     * Register a stream filter.
     */
    public static function register(
        string $name,
        $class,
        bool $overwrite = self::DO_NOT_OVERWRITE
    ): bool {
        if ($overwrite === self::DO_NOT_OVERWRITE &&
            true === self::isRegistered($name)) {
            throw new Exception('Filter %s is already registered.', 0, $name);
        }

        if (is_object($class)) {
            $class = get_class($class);
        }

        if (empty($name)) {
            throw new Exception(
                'Filter name cannot be empty (implementation class is %s).',
                1,
                $class
            );
        }

        if (false === class_exists($class, false)) {
            throw new Exception(
                'Cannot register the %s class for the filter %s ' .
                'because it does not exist.',
                2,
                [$class, $name]
            );
        }

        return stream_filter_register($name, $class);
    }

    /**
     * Append a filter to the list of filters.
     */
    public static function append(
        $stream,
        string $name,
        int $mode       = self::READ,
        $parameters = null
    ) {
        if ($stream instanceof Stream) {
            $stream = $stream->getStream();
        }

        if (null === $parameters) {
            return self::$_resources[$name] = stream_filter_append(
                $stream,
                $name,
                $mode
            );
        }

        return self::$_resources[$name] = stream_filter_append(
            $stream,
            $name,
            $mode,
            $parameters
        );
    }

    /**
     * Prepend a filter to the list of filters.
     */
    public static function prepend(
        $stream,
        string $name,
        int $mode = self::READ,
        $parameters = null
    ) {
        if ($stream instanceof Stream) {
            $stream = $stream->getStream();
        }

        if (null === $parameters) {
            return self::$_resources[$name] = stream_filter_prepend(
                $stream,
                $name,
                $mode
            );
        }

        return self::$_resources[$name] = stream_filter_prepend(
            $stream,
            $name,
            $mode,
            $parameters
        );
    }

    /**
     * Delete a filter.
     */
    public static function remove($streamFilter): bool
    {
        if (!is_resource($streamFilter)) {
            if (isset(self::$_resources[$streamFilter])) {
                $streamFilter = self::$_resources[$streamFilter];
            } else {
                throw new Exception(
                    'Cannot remove the stream filter %s because no resource was ' .
                    'found with this name.',
                    3,
                    $streamFilter
                );
            }
        }

        return stream_filter_remove($streamFilter);
    }

    /**
     * Check if a filter is already registered or not.
     */
    public static function isRegistered(string $name): bool
    {
        return in_array($name, self::getRegistered());
    }

    /**
     * Get all registered filer names.
     */
    public static function getRegistered(): array
    {
        return stream_get_filters();
    }
}

/**
 * Flex entity.
 */
Consistency::flexEntity(Filter::class);
