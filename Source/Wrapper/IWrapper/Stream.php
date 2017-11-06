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

namespace Hoa\Stream\Wrapper\IWrapper;

/**
 * Interface \Hoa\Stream\Wrapper\IWrapper\Stream.
 *
 * Interface for “stream stream wrapper” class.
 */
interface Stream
{
    /**
     * Retrieve the underlaying resource.
     */
    public function stream_cast(int $castAs);

    /**
     * Close a resource.
     * This method is called in response to fclose().
     * All resources that were locked, or allocated, by the wrapper should be
     * released.
     */
    public function stream_close();

    /**
     * Tests for end-of-file on a file pointer.
     * This method is called in response to feof().
     */
    public function stream_eof(): bool;

    /**
     * Flush the output.
     * This method is called in response to fflush().
     * If we have cached data in our stream but not yet stored it into the
     * underlying storage, we should do so now.
     */
    public function stream_flush(): bool;

    /**
     * Advisory file locking.
     * This method is called in response to flock(), when file_put_contents()
     * (when flags contains LOCK_EX), stream_set_blocking() and when closing the
     * stream (LOCK_UN).
     */
    public function stream_lock(int $operation): bool;

    /**
     * Change stream options.
     * This method is called to set metadata on the stream. It is called when
     * one of the following functions is called one a stream URL: touch, chmod,
     * chown or chgrp.
     */
    public function stream_metadata(string $path, int $option, $value): bool;

    /**
     * Open file or URL.
     * This method is called immediately after the wrapper is initialized (f.e.
     * by fopen() and file_get_contents()).
     */
    public function stream_open(string $path, string $mode, int $options, &$openedPath): bool;

    /**
     * Read from stream.
     * This method is called in response to fread() and fgets().
     */
    public function stream_read(int $count): string;

    /**
     * Seek to specific location in a stream.
     * This method is called in response to fseek().
     * The read/write position of the stream should be updated according to the
     * $offset and $whence.
     */
    public function stream_seek(int $offset, int $whence = SEEK_SET): bool;

    /**
     * Change stream options.
     * This method is called to set options on the stream.
     */
    public function stream_set_option(int $option, int $arg1, int $arg2): bool;

    /**
     * Retrieve information about a file resource.
     * This method is called in response to fstat().
     */
    public function stream_stat(): array;

    /**
     * Retrieve the current position of a stream.
     * This method is called in response to ftell().
     */
    public function stream_tell(): int;

    /**
     * Truncate a stream to a given length.
     */
    public function stream_truncate(int $size): bool;

    /**
     * Write to stream.
     * This method is called in response to fwrite().
     */
    public function stream_write(string $data): int;
}
