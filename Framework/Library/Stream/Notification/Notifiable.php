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
 */

namespace Hoa\Stream\Notification {

/**
 * Interface \Hoa\Stream\Notification\Notifiable.
 *
 * Interface for notification stream object.
 *
 * @author     Ivan ENDERLIN <ivan.enderlin@hoa-project.net>
 * @copyright  Copyright (c) 2007, 2010 Ivan ENDERLIN.
 * @license    http://gnu.org/licenses/gpl.txt GNU GPL
 */

interface Notifiable {

    /**
     * Severity: normal, non-error related, notification.
     *
     * @const int
     */
    const SEVERITY_INFORMATION = STREAM_NOTIFY_SEVERITY_INFO;

    /**
     * Severity: non critical error condition, processing may continue.
     *
     * @const int
     */
    const SEVERITY_WARNING     = STREAM_NOTIFY_SEVERITY_WARN;

    /**
     * Severity: a critical error occured, processing cannot continue.
     *
     * @const int
     */
    const SEVERITY_ERROR       = STREAM_NOTIFY_SEVERITY_ERR;



    /**
     * A remote address required for this stream has been resolved, or the
     * resolution failed. See the severity for an indication of which happened.
     *
     * @access  public
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
     * @return  void
     */
    public function resolve ( $severity, $message, $code, $transferred, $max );

    /**
     * A connexion with an external resource has been established.
     *
     * @access  public
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
     * @return  void
     */
    public function connect ( $severity, $message, $code, $transferred, $max );

    /**
     * Additional authorization is required to access the specified resource.
     * Typical issued with the severity level of self::SEVERITY_ERROR.
     *
     * @access  public
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
     * @return  void
     */
    public function authRequired ( $severity, $message, $code, $transferred, $max );

    /**
     * The mime-type of resource has been identified, refer to the message for a
     * description of the discovered type.
     *
     * @access  public
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
     * @return  void
     */
    public function mimeTypeIs ( $severity, $message, $code, $transferred, $max );

    /**
     * The size of the resource has been discovered.
     *
     * @access  public
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
     * @return  void
     */
    public function sizeIs ( $severity, $message, $code, $transferred, $max );

    /**
     * The external resource has directed the stream to an alternate location.
     * Refer to the message.
     *
     * @access  public
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
     * @return  void
     */
    public function redirected ( $severity, $message, $code, $transferred, $max );

    /**
     * Indicate current progress of the stream transfer in the “bytes
     * transferred” and possibly the “bytes max” as well.
     *
     * @access  public
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
     * @return  void
     */
    public function progress ( $severity, $message, $code, $transferred, $max );

    /**
     * There is no more data available on the stream.
     *
     * @access  public
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
     * @return  void
     */
    public function completed ( $severity, $message, $code, $transferred, $max );

    /**
     * A generic error occured on the stream, consult the message and the
     * message code for details.
     *
     * @access  public
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
     * @return  void
     */
    public function failure ( $severity, $message, $code, $transferred, $max );

    /**
     * Authorization has been completed (with or without success).
     *
     * @access  public
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
     * @return  void
     */
    public function authResult ( $severity, $message, $code, $transferred, $max );
}

}
