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
 * Copyright (c) 2007, 2010 Ivan ENDERLIN. All rights reserved.
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
 * @subpackage  Hoa_Stream_Wrapper_Interface
 *
 */

/**
 * Hoa_Stream_Wrapper_Exception
 */
import('Stream.Wrapper.Exception');

/**
 * Hoa_Stream_Wrapper_Interface_File
 */
import('Stream.Wrapper.Interface.File');

/**
 * Hoa_Stream_Wrapper_Interface_Stream
 */
import('Stream.Wrapper.Interface.Stream');

/**
 * Interface Hoa_Stream_Wrapper_Interface.
 *
 * Interface for stream wrapper class.
 *
 * @author      Ivan ENDERLIN <ivan.enderlin@hoa-project.net>
 * @copyright   Copyright (c) 2007, 2010 Ivan ENDERLIN.
 * @license     http://gnu.org/licenses/gpl.txt GNU GPL
 * @since       PHP 5
 * @version     0.1
 * @package     Hoa_Stream
 * @subpackage  Hoa_Stream_Wrapper_Interface
 */

interface   Hoa_Stream_Wrapper_Interface
    extends Hoa_Stream_Wrapper_Interface_File,
            Hoa_Stream_Wrapper_Interface_Stream {

    /**
     * Constructs a new stream wrapper.
     * Called when opening the stream wrapper, right before self::stream_open().
     *
     * @access  public
     * @return  void
     */
    public function __construct ( );
}
