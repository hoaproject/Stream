<?php

/**
 * Hoa
 *
 *
 * @license
 *
 * New BSD License
 *
 * Copyright (c) 2007-2011, Ivan Enderlin. All rights reserved.
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

namespace {

from('Hoa')

/**
 * \Hoa\Stream\Exception
 */
-> import('Stream.Exception')

/**
 * \Hoa\Stream\Context
 */
-> import('Stream.Context');

}

namespace Hoa\Stream {

/**
 * Class \Hoa\Stream.
 *
 * Static register for all streams (files, sockets etc.).
 *
 * @author     Ivan ENDERLIN <ivan.enderlin@hoa-project.net>
 * @copyright  Copyright (c) 2007, 2011 Ivan ENDERLIN.
 * @license    New BSD License
 */

abstract class Stream implements \Hoa\Core\Event\Source {

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
     * @var \Hoa\Stream array
     */
    protected $_bucket            = array();

    /**
     * Static stream register.
     *
     * @var \Hoa\Stream array
     */
    private static $_register     = array();

    /**
     * Whether always use stream resource. Please see the
     * $this->alwaysUseStreamResource() method to get more informations.
     *
     * @var \Hoa\Stream bool
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
     *                                 \Hoa\Stream\Context class).
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
     * @param   string       $streamName    Stream name.
     * @param   \Hoa\Stream  $handler       Stream handler.
     * @param   string       $context       Context ID (please, see the
     *                                      \Hoa\Stream\Context class).
     * @return  array
     * @throw   \Hoa\Stream\Exception
     */
    final private static function &_getStream ( $streamName,
                                                \Hoa\Stream $handler,
                                                $context = null ) {

        $name = md5($streamName);

        if(null !== $context) {

            if(false === \Hoa\Stream\Context::contextExists($context))
                throw new Exception(
                    'Context %s was not previously declared, cannot retrieve ' .
                    'this context.', 0, $context);

            $context = \Hoa\Stream\Context::getInstance($context);
        }

        if(!isset(self::$_register[$name])) {

            self::$_register[$name] = array(
                self::NAME     => $streamName,
                self::HANDLER  => $handler,
                self::RESOURCE => $handler->_open($streamName, $context),
                self::CONTEXT  => $context
            );
            \Hoa\Core\Event::register(
                'hoa://Event/Stream/' . $streamName,
                $handler
            );
            // Add :open-ready?
            \Hoa\Core\Event::register(
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
     * @param   string               $streamName    Stream name (e.g. path or URL).
     * @param   \Hoa\Stream\Context  $context       Context.
     * @return  resource
     * @throw   \Hoa\Core\Exception
     */
    abstract protected function &_open ( $streamName,
                                         \Hoa\Stream\Context $context = null );

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

        \Hoa\Core\Event::notify(
            'hoa://Event/Stream/' . $this->getStreamName() . ':close-before',
            $this,
            new \Hoa\Core\Event\Bucket()
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
     * @return  \Hoa\Stream\Context
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
     * @throw   \Hoa\Stream\Exception
     */
    protected function _setStream ( $stream ) {

        if(!is_resource($stream))
            throw new Exception(
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
     * For example, the \Hoa\File\Read::readAll() method uses file_get_contents()
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

}

namespace {

\Hoa\Core::registerShutDownFunction('\Hoa\Stream\Stream', '_Hoa_Stream');

}
