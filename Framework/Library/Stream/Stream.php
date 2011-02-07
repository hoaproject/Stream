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
 *
 */

/**
 * Hoa_Stream_Exception
 */
import('Stream.Exception');

/**
 * Hoa_Stream_Context
 */
import('Stream.Context');

/**
 * Class Hoa_Stream.
 *
 * Static register for all streams (files, sockets etc.).
 *
 * @author      Ivan ENDERLIN <ivan.enderlin@hoa-project.net>
 * @copyright   Copyright (c) 2007, 2010 Ivan ENDERLIN.
 * @license     http://gnu.org/licenses/gpl.txt GNU GPL
 * @since       PHP 5
 * @version     0.1
 * @package     Hoa_Stream
 */

abstract class Hoa_Stream implements Hoa_Core_Event_Source {

    /**
     * Name index in the stream bucket.
     *
     * @const int
     */
    const NAME     = 0;

    /**
     * Handler index in the stream bucket.
     *
     * @const int
     */
    const HANDLER  = 1;

    /**
     * Resource index in the stream bucket.
     *
     * @const int
     */
    const RESOURCE = 2;

    /**
     * Context index in the stream bucket.
     *
     * @const int
     */
    const CONTEXT  = 4;

    /**
     * Current stream bucket.
     *
     * @var Hoa_Stream array
     */
    protected $_bucket            = array();

    /**
     * Static stream register.
     *
     * @var Hoa_Stream array
     */
    private static $_register     = array();

    /**
     * Whether always use stream resource. Please see the
     * $this->alwaysUseStreamResource() method to get more informations.
     *
     * @var Hoa_Stream bool
     */
    protected $_useStreamResource = false;



    /**
     * Set the current stream.
     * If not exists in the register, try to call the
     * $this->_open() method. Please, see the self::_getStream() method.
     *
     * @access  public
     * @param   string  $streamName    Stream name (e.g. path or URL).
     * @param   string  $context       Context ID (please, see the
     *                                 Hoa_Stream_Context class).
     * @return  void
     */
    public function __construct ( $streamName, $context = null ) {

        $this->_bucket = self::_getStream($streamName, $this, $context);

        return;
    }

    /**
     * Get a stream in the register.
     * If the stream does not exist, try to open it by calling the
     * $handler->_open() method.
     *
     * @access  private
     * @param   string      $streamName    Stream name.
     * @param   Hoa_Stream  $handler       Stream handler.
     * @param   string      $context       Context ID (please, see the
     *                                     Hoa_Stream_Context class).
     * @return  array
     * @throw   Hoa_Stream_Exception
     */
    final private static function &_getStream ( $streamName,
                                                Hoa_Stream $handler,
                                                $context = null ) {

        $name = md5($streamName);

        if(null !== $context) {

            if(false === Hoa_Stream_Context::contextExists($context))
                throw new Hoa_Stream_Exception(
                    'Context %s was not previously declared, cannot retrieve ' .
                    'this context.', 0, $context);

            $context = Hoa_Stream_Context::getInstance($context);
        }

        if(!isset(self::$_register[$name])) {

            self::$_register[$name] = array(
                self::NAME     => $streamName,
                self::HANDLER  => $handler,
                self::RESOURCE => $handler->_open($streamName, $context),
                self::CONTEXT  => $context
            );
            Hoa_Core_Event::register(
                'hoa://Event/Stream/' . $streamName,
                $handler
            );
            // Add :open-ready?
            Hoa_Core_Event::register(
                'hoa://Event/Stream/' . $streamName . ':close-before',
                $handler
            );
        }

        if(null === self::$_register[$name][self::RESOURCE])
            self::$_register[$name][self::RESOURCE] =
                $handler->_open($streamName, $context);

        return self::$_register[$name];
    }

    /**
     * Open the stream and return the associated resource.
     * Note: this method is protected, but do not forget that it could be
     * overloaded into a public context.
     *
     * @access  protected
     * @param   string              $streamName    Stream name (e.g. path or URL).
     * @param   Hoa_Stream_Context  $context       Context.
     * @return  resource
     * @throw   Hoa_Core_Exception
     */
    abstract protected function &_open ( $streamName,
                                         Hoa_Stream_Context $context = null );

    /**
     * Close the current stream.
     * Note: this method is protected, but do not forget that it could be
     * overloaded into a public context.
     *
     * @access  protected
     * @return  bool
     */
    abstract protected function _close ( );

    /**
     * Close the current stream.
     *
     * @access  public
     * @return  void
     */
    final public function close ( ) {

        Hoa_Core_Event::notify(
            'hoa://Event/Stream/' . $this->getStreamName() . ':close-before',
            $this,
            new Hoa_Core_Event_Bucket()
        );

        $this->_close();

        return;
    }

    /**
     * Get the current stream name.
     *
     * @access  public
     * @return  string
     */
    public function getStreamName ( ) {

        return $this->_bucket[self::NAME];
    }

    /**
     * Get the current stream.
     *
     * @access  protected
     * @return  resource
     */
    protected function getStream ( ) {

        return $this->_bucket[self::RESOURCE];
    }

    /**
     * Get the current stream context.
     *
     * @access  protected
     * @return  Hoa_Stream_Context
     */
    protected function getStreamContext ( ) {

        return $this->_bucket[self::CONTEXT];
    }

    /**
     * Set the current stream. Useful to manage a stack of streams (e.g. socket
     * and select). Notice that it could be unsafe to use this method without
     * taking time to think about it two minutes.
     *
     * @access  protected
     * @return  resource
     * @throw   Hoa_Stream_Exception
     */
    protected function _setStream ( $stream ) {

        if(!is_resource($stream))
            throw new Hoa_Stream_Exception(
                'Eh! Read the API documentation! You must think two minutes ' .
                'before using this method…', 1);

        $old                           = $this->_bucket[self::RESOURCE];
        $this->_bucket[self::RESOURCE] = $stream;

        return $old;
    }

    /**
     * Check if the stream is opened.
     *
     * @access  public
     * @return  bool
     */
    public function isOpened ( ) {

        return is_resource($this->getStream());
    }

    /**
     * Set the timeout period.
     *
     * @access  public
     * @param   int     $second    Timeout period in seconds.
     * @param   int     $micro     Timeout period in microseconds.
     * @return  bool
     */
    public function setStreamTimeout ( $seconds, $microseconds = 0 ) {

        return stream_set_timeout($this->getStream(), $seconds, $microseconds);
    }

    /**
     * Set blocking/non-blocking mode.
     *
     * @access  public
     * @param   bool    $mode    Blocking mode.
     * @return  bool
     */
    public function setStreamBlocking ( $mode ) {

        return stream_set_blocking($this->getStream(), (int) $mode);
    }

    /**
     * Set stream buffer.
     * Output using fwrite() (or similar function) is normally buffered at 8 Ko.
     * This means that if there are two processes wanting to write to the same
     * output stream, each is paused after 8 Ko of data to allow the other to
     * write.
     *
     * @access  public
     * @param   int     $buffer    Number of bytes to buffer. If zero, write
     *                             operations are unbuffered. This ensures that
     *                             all writes are completed before other
     *                             processes are allowed to write to that output
     *                             stream.
     * @return  bool
     */
    public function setStreamBuffer ( $buffer ) {

        // Zero means success.
        return 0 === stream_set_write_buffer($this->getStream(), $buffer);
    }

    /**
     * Disable stream buffering.
     * Alias of $this->setBuffer(0).
     *
     * @access  public
     * @return  bool
     */
    public function disableStreamBuffer ( ) {

        return $this->setStreamBuffer(0);
    }

    /**
     * Force to use a stream resource instead of a stream name.
     * For example, the Hoa_File_Read::readAll() method uses file_get_contents()
     * to get all file datas. But this PHP function uses a stream name to work
     * and not a stream resource. It is a really big problem in some cases, e.g.
     * when applying filters, because filters work on a resource and the
     * file_get_contents() starts a new resource (and of course, resources are
     * not shared). So this method switches some methods behaviors.
     *
     * @access  public
     * @param   bool    $useResource    Use a stream resource instead of a
     *                                  stream name in some methods.
     * @return  bool
     */
    public function alwaysUseStreamResource ( $useResource ) {

        $old                      = $this->_useStreamResource;
        $this->_useStreamResource = $useResource;

        return $old;
    }

    /**
     * Know if we must use a stream resource instead of a stream name.
     *
     * @access  public
     * @return  bool
     */
    public function isStreamResourceMustBeUsed ( ) {

        return $this->_useStreamResource;
    }

    /**
     * Get stream wrapper name.
     *
     * @access  public
     * @return  string
     */
    public function getStreamWrapperName ( ) {

        if(false === $pos = strpos($this->getStreamName(), '://'))
            return 'file';

        return substr($this->getStreamName(), 0, $pos);
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

    /**
     * Transform object to string.
     *
     * @access  public
     * @return  string
     */
    public function __toString ( ) {

        return $this->getStreamName();
    }
}


Hoa_Core::registerShutDownFunction('Hoa_Stream', '_Hoa_Stream');
