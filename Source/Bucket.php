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

namespace Hoa\Stream;

/**
 * Class \Hoa\Stream\Bucket.
 *
 * Manipulate stream buckets through brigades.
 */
class Bucket
{
    /**
     * Whether the stream is already a brigade.
     */
    public const IS_A_BRIGADE = true;

    /**
     * Whether the stream is not a brigade.
     */
    public const IS_A_STREAM  = false;

    /**
     * Type of the bucket.
     */
    protected $_type    = null;

    /**
     * Brigade.
     */
    protected $_brigade = null;

    /**
     * Bucket.
     */
    protected $_bucket  = null;



    /**
     * Set a brigade.
     * If a stream is given (with the constant `self::IS_A_STREAM`), it will
     * create a brigade automatically.
     */
    public function __construct(&$brigade, bool $is = self::IS_A_BRIGADE, string $buffer = '')
    {
        $this->setType($is);

        if (self::IS_A_BRIGADE === $this->getType()) {
            $this->setBrigade($brigade);
        } else {
            $this->setBucket(stream_bucket_new($brigade, $buffer));
            $bucket = $this->getBucket();
            $this->setBrigade($bucket);
        }

        return;
    }

    /**
     * Test the end-of-bucket.
     * When testing, set the new bucket object.
     */
    public function eob(): bool
    {
        $this->_bucket = null;

        return false == $this->getBucket();
    }

    /**
     * Append bucket to the brigade.
     */
    public function append(Bucket $bucket)
    {
        stream_bucket_append($this->getBrigade(), $bucket->getBucket());

        return;
    }

    /**
     * Prepend bucket to the brigade.
     */
    public function prepend(Bucket $bucket)
    {
        stream_bucket_prepend($this->getBrigade(), $bucket->getBucket());

        return;
    }

    /**
     * Set type.
     */
    protected function setType(bool $type): ?bool
    {
        $old         = $this->_type;
        $this->_type = $type;

        return $old;
    }

    /**
     * Get type.
     */
    public function getType(): ?bool
    {
        return $this->_type;
    }

    /**
     * Set bucket data.
     */
    public function setData(string $data): ?string
    {
        $old                        = $this->getBucket()->data;
        $this->getBucket()->data    = $data;
        $this->getBucket()->datalen = strlen($this->getBucket()->data);

        return $old;
    }

    /**
     * Get bucket data.
     */
    public function getData(): ?string
    {
        if (null === $this->getBucket()) {
            return null;
        }

        return $this->getBucket()->data;
    }

    /**
     * Get bucket length.
     */
    public function getLength(): int
    {
        if (null === $this->getBucket()) {
            return 0;
        }

        return $this->getBucket()->datalen;
    }

    /**
     * Set the brigade.
     */
    protected function setBrigade(&$brigade)
    {
        $old            = $this->_brigade;
        $this->_brigade = $brigade;

        return $old;
    }

    /**
     * Get the brigade.
     */
    public function getBrigade()
    {
        return $this->_brigade;
    }

    /**
     * Set bucket.
     */
    protected function setBucket($bucket)
    {
        $old           = $this->_bucket;
        $this->_bucket = $bucket;

        return $old;
    }

    /**
     * Get the current bucket.
     */
    protected function getBucket()
    {
        if (null === $this->_bucket && self::IS_A_BRIGADE === $this->getType()) {
            $this->_bucket = stream_bucket_make_writeable($this->getBrigade());
        }

        return $this->_bucket;
    }
}
