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

namespace Hoa\Stream\IStream;

/**
 * Interface \Hoa\Stream\IStream\Structural.
 *
 * Interface for structural input/output.
 */
interface Structural extends Stream
{
    /**
     * Select root of the document: :root.
     */
    public function selectRoot(): self;

    /**
     * Select any elements: *.
     */
    public function selectAnyElements(): array;

    /**
     * Select elements of type E: E.
     */
    public function selectElements(string $E = null): array;

    /**
     * Select F elements descendant of an E element: E F.
     */
    public function selectDescendantElements(string $F = null): array;

    /**
     * Select F elements children of an E element: E > F.
     */
    public function selectChildElements(string $F = null): array;

    /**
     * Select an F element immediately preceded by an E element: E + F.
     */
    public function selectAdjacentSiblingElement(string $F): Structural;

    /**
     * Select F elements preceded by an E element: E ~ F.
     */
    public function selectSiblingElements(string $F = null): array;

    /**
     * Execute a query selector and return the first result.
     */
    public function querySelector(string $query): Structural;

    /**
     * Execute a query selector and return one or many results.
     */
    public function querySelectorAll(string $query): array;
}
