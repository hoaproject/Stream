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
 * @subpackage  Hoa_Stream_Filter
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
 * Hoa_Stream_Filter_Default
 */
import('Stream.Filter.Default');

/**
 * Class Hoa_Stream_Filter.
 *
 * Proposes some methods to handle filter.
 *
 * @author      Ivan ENDERLIN <ivan.enderlin@hoa-project.net>
 * @copyright   Copyright (c) 2007, 2008 Ivan ENDERLIN.
 * @license     http://gnu.org/licenses/gpl.txt GNU GPL
 * @since       PHP 5
 * @version     0.1
 * @package     Hoa_Stream
 * @subpackage  Hoa_Stream_Filter
 */

class Hoa_Stream_Filter {

    /**
     * Overwrite filter if already exists.
     *
     * @const bool
     */
    const OVERWRITE        = true;

    /**
     * Do not overwrite filter if already exists.
     *
     * @const bool
     */
    const DO_NOT_OVERWRITE = false;

    /**
     * Filter should only be applied when reading.
     *
     * @const int
     */
    const READ             = STREAM_FILTER_READ;

    /**
     * Filter should only be applied when writing.
     *
     * @const int
     */
    const WRITE            = STREAM_FILTER_WRITE;

    /**
     * Filter should be applied when reading and writing.
     *
     * @const int
     */
    const READ_AND_WRITE   = STREAM_FILTER_ALL;

    /**
     * Filters resources register.
     *
     * @var Hoa_Stream_Filter array
     */
    protected static $_resources = array();



    /**
     * Register a stream filter.
     *
     * @access  public
     * @param   string  $name         Filter name.
     * @param   mixed   $class        Class name or instance.
     * @param   bool    $overwrite    Overwrite filter if alreaady exists or
     *                                not. Given by self::*OVERWRITE constants.
     * @return  bool
     * @throw   Hoa_Stream_Exception
     */
    public static function register ( $name, $class, $overwrite = self::DO_NOT_OVERWRITE ) {

        if(   $overwrite === self::DO_NOT_OVERWRITE
           && true       === self::isRegistered($name))
            throw new Hoa_Stream_Exception(
                'Filter %s is already registered.', 0, $name);

        if(empty($name))
            throw new Hoa_Stream_Exception(
                'Filter name cannot be empty.', 1);

        if(is_object($class))
            $class = get_class($class);

        return stream_filter_register($name, $class);
    }

    /**
     * Append a filter to the list of filters.
     *
     * @access  public
     * @param   resource  $stream        Stream which received the filter.
     * @param   string    $name          Filter name.
     * @param   int       $mode          self::READ or self::WRITE.
     * @param   mixed     $parameters    Parameters.
     * @return  resource
     * @throw   Hoa_Stream_Exception
     */
    public static function append ( $stream,            $name,
                                    $mode = self::READ, $parameters = null ) {

        if(!is_resource($stream))
            throw new Hoa_Stream_Exception(
                'The stream must be a resource, given %s.', 2, gettype($stream));

        if(null === $parameters)
            return self::$_resources[$name] =
                       stream_filter_append($stream, $name, $mode);

        return self::$_resources[$name] =
                   stream_filter_append($stream, $name, $mode, $parameters);
    }

    /**
     * Prepend a filter to the list of filters.
     *
     * @access  public
     * @param   resource  $stream        Stream which received the filter.
     * @param   string    $name          Filter name.
     * @param   int       $mode          self::READ or self::WRITE.
     * @param   mixed     $parameters    Parameters.
     * @return  resource
     * @throw   Hoa_Stream_Exception
     */
    public static function prepend ( $stream,            $name,
                                     $mode = self::READ, $parameters = null ) {

        if(!is_resource($stream))
            throw new Hoa_Stream_Exception(
                'The stream must be a resource, given %s.', 3, gettype($stream));

        if(null === $parameters)
            return self::$_resources[$name] =
                       stream_filter_prepend($stream, $name, $mode);

        return self::$_resources[$name] =
                   stream_filter_prepend($stream, $name, $mode, $parameters);
    }

    /**
     * Delete a filter.
     *
     * @access  public
     * @param   mixed   $streamFilter    Stream filter resource or name.
     * @return  bool
     * @throw   Hoa_Stream_Exception
     */
    public static function remove ( $streamFilter ) {

        if(!is_resource($streamFilter))
            if(isset(self::$_resources[$streamFilter]))
                $stream = self::$_resources[$streamFilter];
            else
                throw new Hoa_Stream_Exception(
                    'Cannot remove the stream filter %s because no resource was ' . 
                    'found with this name.', 4, $streamFilter);

        return stream_filter_remove($stream);
    }

    /**
     * Check if a filter is already registered or not.
     *
     * @access  public
     * @param   string  $name    Filter name.
     * @return  bool
     */
    public static function isRegistered ( $name ) {

        return in_array($name, self::getRegistered());
    }

    /**
     * Get all registered filer names.
     *
     * @access  public
     * @return  array
     */
    public static function getRegistered ( ) {

        return stream_get_filters();
    }
}
