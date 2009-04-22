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
 * Static register for all streams (files, sockets etc.).
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
     * Handler register index.
     *
     * @const int
     */
    const HANDLER  = 0;

    /**
     * Resource register index.
     *
     * @const int
     */
    const RESOURCE = 1;

    /**
     * Current stream.
     *
     * @var Hoa_Stream resource
     */
    protected      $_stream   = null;

    /**
     * Static stream register.
     *
     * @var Hoa_Stream array
     */
    private static $_register = array();



    /**
     * Set the current stream.
     * If not exists in the register, try to call the
     * $this->open() method. Please, see the self::_getStream() method.
     *
     * @access  public
     * @param   string  $streamName    Stream name (e.g. path or URL).
     * @return  void
     */
    public function __construct ( $streamName ) {

        $this->_stream = self::_getStream($streamName, $this);

        return;
    }

    /**
     * Get a stream in the register.
     * If the stream does not exist, try to open it by calling the
     * $handler->open() method.
     *
     * @access  private
     * @param   string      $streamName    Stream name.
     * @param   Hoa_Stream  $handler       Stream handler.
     * @return  resource
     */
    final private static function &_getStream ( $streamName, Hoa_Stream $handler ) {

        $name = md5($streamName);

        if(!isset(self::$_register[$name]))
            self::$_register[$name] = array(
                self::HANDLER  => $handler,
                self::RESOURCE => $handler->open($streamName)
            );

        return self::$_register[$name][self::RESOURCE];
    }

    /**
     * Open the stream and return the associated resource.
     * Note: this method is protected, but do not forget that it could be
     * overloaded into a public context.
     *
     * @access  protected
     * @param   string     $streamName    Stream name (e.g. path or URL).
     * @return  resource
     * @throw   Hoa_Stream_Exception
     */
    abstract protected function &open ( $streamName );

    /**
     * Close the current stream.
     * Note: this method is protected, but do not forget that it could be
     * overloaded into a public context.
     * @todo : Closing a stream should delete it from the register, isn't it?
     *
     * @access  protected
     * @return  bool
     */
    abstract protected function close ( );

    /**
     * Get the current stream.
     *
     * @access  protected
     * @return  resource
     */
    protected function getStream ( ) {

        return $this->_stream;
    }

    /**
     * Call the $handler->close() method on each stream in the static stream
     * register.
     * This method does not check the return value of $handler->close(). Thus,
     * if a stream is persistent, the $handler->close() should do anything. It
     * is a very generic method.
     *
     * @access  public
     * @return  void
     */
    final public static function _Hoa_Stream ( ) {

        foreach(self::$_register as $i => $entry)
            $entry[self::HANDLER]->close();

        return;
    }
}

Hoa_Framework::registerShutDownFunction('Hoa_Stream', '_Hoa_Stream');
