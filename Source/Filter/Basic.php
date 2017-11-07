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

use Hoa\Stream;

/**
 * Class \Hoa\Stream\Filter\Basic.
 *
 * Basic filter. Force to implement some methods.
 * Actually, it extends the php_user_filter class.
 */
abstract class Basic extends \php_user_filter implements Stream\IStream\Stream
{
    /**
     * Filter processed successfully with data available in the out bucket
     * brigade.
     */
    public const PASS_ON          = PSFS_PASS_ON;

    /**
     * Filter processed successfully, however no data was available to return.
     * More data is required from the stream or prior filter.
     */
    public const FEED_ME          = PSFS_FEED_ME;

    /**
     * The filter experienced and unrecoverable error and cannot continue.
     */
    public const FATAL_ERROR      = PSFS_ERR_FATAL;

    /**
     * Regular read/write.
     */
    public const FLAG_NORMAL      = PSFS_FLAG_NORMAL;

    /**
     * An incremental flush.
     */
    public const FLAG_FLUSH_INC   = PSFS_FLAG_FLUSH_INC;

    /**
     * Final flush prior to closing.
     */
    public const FLAG_FLUSH_CLOSE = PSFS_FLAG_FLUSH_CLOSE;



    /**
     * Filter data.
     * This method is called whenever data is read from or written to the attach
     * stream.
     */
    public function filter($in, $out, &$consumed, $closing)
    {
        $iBucket = new Stream\Bucket($in);
        $oBucket = new Stream\Bucket($out);

        while (false === $iBucket->eob()) {
            $consumed += $iBucket->getLength();
            $oBucket->append($iBucket);
        }

        unset($iBucket);
        unset($oBucket);

        return self::PASS_ON;
    }

    /**
     * Called during instanciation of the filter class object.
     */
    public function onCreate(): bool
    {
        return true;
    }

    /**
     * Called upon filter shutdown (typically, this is also during stream
     * shutdown), and is executed after the flush method is called.
     */
    public function onClose()
    {
        return;
    }

    /**
     * Set the filter name.
     */
    public function setName(string $name): ?string
    {
        $old              = $this->filtername;
        $this->filtername = $name;

        return $old;
    }

    /**
     * Set the filter parameters.
     */
    public function setParameters($parameters)
    {
        $old          = $this->params;
        $this->params = $parameters;

        return $old;
    }

    /**
     * Get the filter name.
     */
    public function getName(): ?string
    {
        return $this->filtername;
    }

    /**
     * Get the filter parameters.
     */
    public function getParameters()
    {
        return $this->params;
    }

    /**
     * Get the stream resource being filtered.
     * Maybe available only during **filter** calls when the closing parameter
     * is set to false.
     */
    public function getStream()
    {
        return isset($this->stream) ? $this->stream : null;
    }
}
