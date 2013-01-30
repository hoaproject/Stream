<?php

/**
 * Hoa
 *
 *
 * @license
 *
 * New BSD License
 *
 * Copyright © 2007-2013, Ivan Enderlin. All rights reserved.
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
 * @author     Ivan Enderlin <ivan.enderlin@hoa-project.net>
 * @copyright  Copyright © 2007-2013 Ivan Enderlin.
 * @license    New BSD License
 */

abstract class Stream implements \Hoa\Core\Event\Listenable {

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
    protected $_bucket          = array();

    /**
     * Static stream register.
     *
     * @var \Hoa\Stream array
     */
    private static $_register   = array();

    /**
     * Buffer size (default is 8Ko).
     *
     * @var \Hoa\Stream bool
     */
    protected $_bufferSize      = 8192;

    /**
     * Original stream name, given to the stream constructor.
     *
     * @var \Hoa\Stream string
     */
    protected $_streamName      = null;

    /**
     * Context name.
     *
     * @var \Hoa\Stream string
     */
    protected $_context         = null;

    /**
     * Whether the opening has been differed.
     *
     * @var \Hoa\Stream bool
     */
    protected $_hasBeenDiffered = false;

    /**
     * Listeners.
     *
     * @var \Hoa\Core\Event\Listener object
     */
    protected $_on              = null;



    /**
     * Set the current stream.
     * If not exists in the register, try to call the
     * $this->_open() method. Please, see the self::_getStream() method.
     *
     * @access  public
     * @param   string  $streamName    Stream name (e.g. path or URL).
     * @param   string  $context       Context ID (please, see the
     *                                 \Hoa\Stream\Context class).
     * @param   bool    $wait          Differ opening or not.
     * @return  void
     */
    public function __construct ( $streamName, $context = null, $wait = false ) {

        $this->_streamName      = $streamName;
        $this->_context         = $context;
        $this->_hasBeenDiffered = $wait;
        $this->_on              = new \Hoa\Core\Event\Listener($this, array(
            'authrequire',
            'authresult',
            'complete',
            'connect',
            'failure',
            'mimetype',
            'progress',
            'redirect',
            'resolve',
            'size'
        ));

        if(true === $wait)
            return;

        $this->open();

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
                                                Stream $handler,
                                                $context = null ) {

        $name = md5($streamName);

        if(null !== $context) {

            if(false === Context::contextExists($context))
                throw new Exception(
                    'Context %s was not previously declared, cannot retrieve ' .
                    'this context.', 0, $context);

            $context = Context::getInstance($context);
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
    abstract protected function &_open ( $streamName, Context $context = null );

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
     * Open the stream.
     *
     * @access  public
     * @return  \Hoa\Stream
     * @throw   \Hoa\Stream\Exception
     */
    final public function open ( ) {

        $context = $this->_context;

        if(true === $this->_hasBeenDiffered) {

            if(null === $context) {

                $handle = Context::getInstance(uniqid());
                $handle->setParameters(array(
                    'notification' => array($this, '_notify')
                ));
                $context = $handle->getId();
            }
            elseif(true === Context::contextExists($context)) {

                $handle     = Context::getInstance($context);
                $parameters = $handle->getParameters();

                if(!isset($parameters['notification']))
                    $handle->setParameters(array(
                        'notification' => array($this, '_notify')
                    ));
            }
        }

        $this->_bucket = self::_getStream(
            $this->_streamName,
            $this,
            $context
        );

        return $this;
    }

    /**
     * Close the current stream.
     *
     * @access  public
     * @return  void
     */
    final public function close ( ) {

        $streamName = $this->getStreamName();
        $name       = md5($streamName);

        if(!isset(self::$_register[$name]))
            return;

        \Hoa\Core\Event::notify(
            'hoa://Event/Stream/' . $streamName . ':close-before',
            $this,
            new \Hoa\Core\Event\Bucket()
        );

        if(false === $this->_close())
            return;

        unset(self::$_register[$name]);
        $this->_bucket[self::HANDLER] = null;
        unset($this->_on);
        \Hoa\Core\Event::unregister(
            'hoa://Event/Stream/' . $streamName
        );
        \Hoa\Core\Event::unregister(
            'hoa://Event/Stream/' . $streamName . ':close-before'
        );

        return;
    }

    /**
     * Get the current stream name.
     *
     * @access  public
     * @return  string
     */
    public function getStreamName ( ) {

        if(empty($this->_bucket))
            return null;

        return $this->_bucket[self::NAME];
    }

    /**
     * Get the current stream.
     *
     * @access  protected
     * @return  resource
     */
    protected function getStream ( ) {

        if(empty($this->_bucket))
            return null;

        return $this->_bucket[self::RESOURCE];
    }

    /**
     * Get the current stream context.
     *
     * @access  protected
     * @return  \Hoa\Stream\Context
     */
    public function getStreamContext ( ) {

        if(empty($this->_bucket))
            return null;

        return $this->_bucket[self::CONTEXT];
    }

    /**
     * Get stream handler according to its name.
     *
     * @access  public
     * @param   string  $streamName    Stream name.
     * @return  \Hoa\Stream
     */
    public static function getStreamHandler ( $streamName ) {

        $name = md5($streamName);

        if(!isset(self::$_register[$name]))
            return null;

        return self::$_register[$name][self::HANDLER];
    }

    /**
     * Set the current stream. Useful to manage a stack of streams (e.g. socket
     * and select). Notice that it could be unsafe to use this method without
     * taking time to think about it two minutes.
     *
     * @access  public
     * @return  resource
     * @throw   \Hoa\Stream\Exception
     */
    public function _setStream ( $stream ) {

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
        $out = 0 === stream_set_write_buffer($this->getStream(), $buffer);

        if(true === $out)
            $this->_bufferSize = $buffer;

        return $out;
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
     * Get stream buffer size.
     *
     * @access  public
     * @return  int
     */
    public function getStreamBufferSize ( ) {

        return $this->_bufferSize;
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
     * Get stream meta data.
     *
     * @access  public
     * @return  array
     */
    public function getStreamMetaData ( ) {

        return stream_get_meta_data($this->getStream());
    }

    /**
     * Attach a callable to this listenable object.
     *
     * @access  public
     * @param   string  $listenerId    Listener ID.
     * @param   mixed   $callable      Callable.
     * @return  \Hoa\Stream
     * @return  \Hoa\Core\Exception
     */
    public function on ( $listenerId, $callable ) {

        $this->_on->attach($listenerId, $callable);

        return $this;
    }

    /**
     * Notification callback.
     *
     * @access  public
     * @param   int     $ncode          Notification code. Please, see
     *                                  STREAM_NOTIFY_* constants.
     * @param   int     $severity       Severity. Please, see
     *                                  STREAM_NOTIFY_SEVERITY_* constants.
     * @param   string  $message        Message.
     * @param   int     $code           Message code.
     * @param   int     $transferred    If applicable, the number of transferred
     *                                  bytes.
     * @param   int     $max            If applicable, the number of max bytes.
     * @return  void
     */
    public function _notify ( $ncode, $severity, $message, $code, $transferred,
                              $max ) {

        static $_map = array(
            STREAM_NOTIFY_AUTH_REQUIRED => 'authrequire',
            STREAM_NOTIFY_AUTH_RESULT   => 'authresult',
            STREAM_NOTIFY_COMPLETED     => 'complete',
            STREAM_NOTIFY_CONNECT       => 'connect',
            STREAM_NOTIFY_FAILURE       => 'failure',
            STREAM_NOTIFY_MIME_TYPE_IS  => 'mimetype',
            STREAM_NOTIFY_PROGRESS      => 'progress',
            STREAM_NOTIFY_REDIRECTED    => 'redirect',
            STREAM_NOTIFY_RESOLVE       => 'resolve',
            STREAM_NOTIFY_FILE_SIZE_IS  => 'size'
        );

        $this->_on->fire($_map[$ncode], new \Hoa\Core\Event\Bucket(array(
            'code'        => $code,
            'severity'    => $severity,
            'message'     => $message,
            'code'        => $code,
            'transferred' => $transferred,
            'max'         => $max
        )));

        return;
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

        foreach(self::$_register as $entry)
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

    /**
     * Close the stream when destructing.
     *
     * @access  public
     * @return  void
     */
    public function __destruct ( ) {

        $this->close();

        return;
    }
}

/**
 * Class \Hoa\Stream\_Protocol.
 *
 * hoa://Library/Stream component.
 *
 * @author     Ivan Enderlin <ivan.enderlin@hoa-project.net>
 * @copyright  Copyright © 2007-2013 Ivan Enderlin.
 * @license    New BSD License
 */

class _Protocol extends \Hoa\Core\Protocol {

    /**
     * Component's name.
     *
     * @var \Hoa\Core\Protocol string
     */
    protected $_name = 'Stream';



    /**
     * ID of the component.
     *
     * @access  public
     * @param   string  $id    ID of the component.
     * @return  mixed
     */
    public function reachId ( $id ) {

        return Stream::getStreamHandler($id);
    }
}

}

namespace {

\Hoa\Core::registerShutDownFunction('\Hoa\Stream\Stream', '_Hoa_Stream');

/**
 * Add the hoa://Library/Stream component. Should be use to reach/get an entry
 * in the \Hoa\Stream register.
 */
$protocol              = \Hoa\Core::getInstance()->getProtocol();
$protocol['Library'][] = new \Hoa\Stream\_Protocol();

}
