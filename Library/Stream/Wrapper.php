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
 * @subpackage  Hoa_Stream_Wrapper
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
 * Hoa_Stream_Wrapper_Interface
 */
import('Stream.Wrapper.Interface');

/**
 * Class Hoa_Stream_Wrapper.
 *
 * Manipulate wrappers.
 *
 * @author      Ivan ENDERLIN <ivan.enderlin@hoa-project.net>
 * @copyright   Copyright (c) 2007, 2008 Ivan ENDERLIN.
 * @license     http://gnu.org/licenses/gpl.txt GNU GPL
 * @since       PHP 5
 * @version     0.1
 * @package     Hoa_Stream
 * @subpackage  Hoa_Stream_Wrapper
 */

class Hoa_Stream_Wrapper {

    /**
     * Register a wrapper.
     *
     * @access  public
     * @param   string  $protocol     The wrapper name to be registered.
     * @param   string  $classname    Classname which implements the $protocol.
     * @param   int     $flags        Should be set to STREAM_IS_URL if
     *                                $protocol is a URL protocol. Default is 0,
     *                                local stream.
     * @return  bool
     * @throw   Hoa_Stream_Exception
     */
    public static function register ( $protocol, $classname, $flags = 0 ) {

        if(true === self::isRegistered($protocol))
            throw new Hoa_Stream_Exception(
                'The protocol %s is already registered.', 0, $protocol);

        if(false === class_exists($classname))
            throw new Hoa_Stream_Exception(
                'Cannot register the %s class because it is not found.',
                1, $classname);

        return stream_wrapper_register($protocol, $classname, $flags);
    }

    /**
     * Unregister a wrapper.
     *
     * @access  public
     * @param   string  $protocol    The wrapper name to be unregistered.
     * @return  bool
     */
    public static function unregister ( $protocol ) {

        return stream_wrapper_unregister($protocol);
    }

    /**
     * Restore a previously unregistered build-in wrapper.
     *
     * @access  public
     * @param   string  $protocol    The wrapper name to be restored.
     * @return  bool
     */
    public static function restore ( $protocol ) {

        return stream_wrapper_restore($protocol);
    }

    /**
     * Check if a protocol is registered or not.
     *
     * @access  public
     * @param   string  $protocol    Protocol name.
     */
    public static function isRegistered ( $protocol ) {

        return in_array($name, self::getRegistered());
    }

    /**
     * Get all registered wrapper.
     *
     * @access  public
     * @return  array
     */
    public static function getRegistered ( ) {

        return stream_get_wrappers();
    }
}
