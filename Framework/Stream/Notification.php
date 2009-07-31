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
 * @subpackage  Hoa_Stream_Notification
 *
 */

/**
 * Hoa_Framework
 */
require_once 'Framework.php';

/**
 * Hoa_Stream_Notification_Exception
 */
import('Stream.Notification.Exception');

/**
 * Class Hoa_Stream_Notification.
 *
 * Manage stream notifications.
 *
 * @author      Ivan ENDERLIN <ivan.enderlin@hoa-project.net>
 * @copyright   Copyright (c) 2007, 2008 Ivan ENDERLIN.
 * @license     http://gnu.org/licenses/gpl.txt GNU GPL
 * @since       PHP 5
 * @version     0.1
 * @package     Hoa_Stream
 * @subpackage  Hoa_Stream_Notification
 */

class Hoa_Stream_Notification {

    /**
     * A remove address required for this stream has been resolved, or the
     * resolution failed.
     *
     * @const int
     */
    const RESOLVE       = STREAM_NOTIFY_RESOLVE;

    /**
     * A connection with an external resource has been established.
     *
     * @const int
     */
    const CONNECT       = STREAM_NOTIFY_CONNECT;

    /**
     * Additional authorization is required to access the specified resource.
     *
     * @const int
     */
    const AUTH_REQUIRED = STREAM_NOTIFY_AUTH_REQUIRED;

    /**
     * The mime-type of resource has been identified.
     *
     * @const int
     */
    const MIME_TYPE_IS  = STREAM_NOTIFY_MIME_TYPE_IS;

    /**
     * The size of the resource has been discovered.
     *
     * @const int
     */
    const FILE_SIZE_IS  = STREAM_NOTIFY_FILE_SIZE_IS;

    /**
     * The external resource has redirected the stream to an alternate location.
     *
     * @const int
     */
    const REDIRECTED    = STREAM_NOTIFY_REDIRECTED;

    /**
     * Indicate current progress of the stream transfer.
     *
     * @const int
     */
    const PROGRESS      = STREAM_NOTIFY_PROGRESS;

    /**
     * There is no more data available on the stream.
     *
     * @const int
     */
    const COMPLETED     = STREAM_NOTIFY_COMPLETED;

    /**
     * A generic error occured on the stream.
     *
     * @const int
     */
    const FAILURE       = STREAM_NOTIFY_FAILURE;

    /**
     * Authorization has been completed (with or without success).
     *
     * @const int
     */
    const AUTH_RESULT   = STREAM_NOTIFY_AUTH_RESULT;

    /**
     * Stream.
     *
     * @var Hoa_Stream_Notification resource
     */
    protected $_stream  = null;

    /**
     * Notifiers list.
     *
     * @var Hoa_Stream_Notification array
     */
    private $_notifiers = array();



    /**
     * Set the stream.
     *
     * @access  public
     * @param   resource  $stream    Stream.
     * @return  void
     */
    public function __construct ( $stream ) {

        $this->setStream($stream);
        stream_context_set_params(
            $this->getStream(),
            array('notification' => array($this, 'callback'))
        );

        return;
    }

    /**
     * Register a notifier.
     *
     * @access  public
     * @param   Hoa_Stream_Notification_Interface  $notifier    Notifier.
     * @return  void
     * @throw   Hoa_Stream_Notification_Exception
     */
    public function register ( Hoa_Stream_Notification_Interface $notifier ) {

        $index = get_class($index);

        if(true === self::isRegistered($index))
            throw new Hoa_Stream_Notification_Exception(
                'Notification %s is already registered.', 0, $index);

        self::$_notifiers[$index] = $notifier;

        return;
    }

    /**
     * Unregister a notifier.
     *
     * @access  public
     * @param   mixed   $notifier    Notifier instance or name (i.e. classname).
     * @return  void
     * @throw   Hoa_Stream_Notification_Exception
     */
    public function unregister ( $notifier ) {

        if($notifier instanceof Hoa_Stream_Notification_Interface)
            $notifier = get_class($notifier);

        unset(self::$_notifiers[$notifier]);

        return;
    }

    /**
     * Check if notifier is already registered or not.
     *
     * @access  public
     * @param   mixed   $notifier    Notifier instance or name (i.e. classname).
     * @return  bool
     */
    public function isRegistered ( $notifier ) {

        if($notifier instanceof Hoa_Stream_Notification_Interface)
            $notifier = get_class($notifier);

        return isset(self::$_notifiers[$notifier]);
    }

    /**
     * Callback notification method.
     *
     * @access  public
     * @param   int     $notifCode      One of the self::* constants.
     * @param   int     $severity       One of the self::SEVIRITY_* constants.
     * @param   string  $message        Passed if a descriptive message is
     *                                  available for the event.
     * @param   int     $code           Passed if a descriptive messsage code is
     *                                  available for the event. The meaning of
     *                                  this value is dependent on the specific
     *                                  wrapper in use.
     * @param   int     $transferred    If applicable, the transferred bytes
     *                                  number.
     * @param   int     $max            If applicable, the max bytes number.
     * @throw   Hoa_Stream_Notification_Exception
     * @return  void
     */
    public function callback ( $notifCode, $severity,    $message,
                               $code,      $transferred, $max ) {

        switch($notifCode) {

            case self::RESOLVE:
                foreach(self::$_notifiers as $i => $notifier)
                    $notifier->resolve($severity, $message, $code, $transferred, $max);
              break;

            case self::CONNECT:
                foreach(self::$_notifiers as $i => $notifier)
                    $notifier->connect($severity, $message, $code, $transferred, $max);
              break;

            case self::AUTH_REQUIRED:
                foreach(self::$_notifiers as $i => $notifier)
                    $notifier->authRequired($severity, $message, $code, $transferred, $max);
              break;

            case self::MIME_TYPE_IS:
                foreach(self::$_notifiers as $i => $notifier)
                    $notifier->mimeTypeIs($severity, $message, $code, $transferred, $max);
              break;

            case self::SIZE_IS:
                foreach(self::$_notifiers as $i => $notifier)
                    $notifier->sizeIs($severity, $message, $code, $transferred, $max);
              break;

            case self::REDIRECTED:
                foreach(self::$_notifiers as $i => $notifier)
                    $notifier->redirected($severity, $message, $code, $transferred, $max);
              break;

            case self::PROGRESS:
                foreach(self::$_notifiers as $i => $notifier)
                    $notifier->progress($severity, $message, $code, $transferred, $max);
              break;

            case self::COMPLETED:
                foreach(self::$_notifiers as $i => $notifier)
                    $notifier->completed($severity, $message, $code, $transferred, $max);
              break;

            case self::FAILURE:
                foreach(self::$_notifiers as $i => $notifier)
                    $notifier->failure($severity, $message, $code, $transferred, $max);
              break;

            case self::AUTH_RESULT:
                foreach(self::$_notifiers as $i => $notifier)
                    $notifier->authResult($severity, $message, $code, $transferred, $max);
              break;

            default:
                throw new Hoa_Stream_Notification_Exception(
                    'Unknown notification code : %d.', 1, $notifCode);
        }

        return;
    }

    /**
     * Set the stream.
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
     * Get the stream.
     *
     * @access  protected
     * @return  resource
     */
    protected function getStream ( ) {

        return $this->_stream;
    }
}
