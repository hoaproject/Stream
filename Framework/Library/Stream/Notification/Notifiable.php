<?php

/**
 * Hoa
 *
 *
 * @license
 *
 * New BSD License
 *
 * Copyright © 2007-2011, Ivan Enderlin. All rights reserved.
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

namespace Hoa\Stream\Notification {

/**
 * Interface \Hoa\Stream\Notification\Notifiable.
 *
 * Interface for notification stream object.
 *
 * @author     Ivan Enderlin <ivan.enderlin@hoa-project.net>
 * @copyright  Copyright © 2007-2011 Ivan Enderlin.
 * @license    New BSD License
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
