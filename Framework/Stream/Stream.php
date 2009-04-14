<?php

/**
 * Hoa Framework
 *
 *
 * @license
 *
 * GNU General Public License
 *
 * This file is part of Hoa Open Accessibility.
 * Copyright (c) 2007, 2008 Ivan ENDERLIN. All rights reserved.
 *
 * HOA Open Accessibility is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * HOA Open Accessibility is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with HOA Open Accessibility; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 *
 * @category    Framework
 * @package     Hoa_Stream
 *
 */

/**
 * Hoa_Framework
 */
require_once 'Framework.php';

/**
 * Hoa_Stream_Exception
 */
import('Stream.Exception');

/**
 * Class Hoa_Stream.
 *
 * A simple stream (i.e. a resource) wrapper.
 *
 * @author      Ivan ENDERLIN <ivan.enderlin@hoa-project.net>
 * @copyright   Copyright (c) 2007, 2008 Ivan ENDERLIN.
 * @license     http://gnu.org/licenses/gpl.txt GNU GPL
 * @since       PHP 5
 * @version     0.1
 * @package     Hoa_Stream
 */

abstract class Hoa_Stream {

    /**
     * Stream.
     *
     * @var Hoa_Stream resource
     */
    protected $_stream = null;



    /**
     * Constructor.
     * Save stream.
     *
     * @access  public
     * @param   resource  $stream    Stream.
     * @return  void
     */
    public function __construct ( $stream ) {

        $this->setStream($stream)e

        return;
    }

    /**
     * Set stream.
     *
     * @access  protected
     * @param   resource   $stream    Stream.
     * @return  resource
     */
    protected function setStream ( $stream ) {

        $old           = $this->_stream;
        $this->_stream = $stream;

        return $old;
    }

    /**
     * Get stream.
     *
     * @access  public
     * @return  resource
     */
    public function getStream ( ) {

        return $this->_stream;
    }
}
