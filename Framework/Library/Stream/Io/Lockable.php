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
 * Copyright (c) 2007, 2009 Ivan ENDERLIN. All rights reserved.
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
 * @subpackage  Hoa_Stream_Io_Lockable
 *
 */

/**
 * Hoa_Framework
 */
require_once 'Framework.php';

/**
 * Interface Hoa_Stream_Io_Lockable.
 *
 * Interface for lockable input/output.
 *
 * @author      Ivan ENDERLIN <ivan.enderlin@hoa-project.net>
 * @copyright   Copyright (c) 2007, 2009 Ivan ENDERLIN.
 * @license     http://gnu.org/licenses/gpl.txt GNU GPL
 * @since       PHP 5
 * @version     0.1
 * @package     Hoa_Stream
 * @subpackage  Hoa_Stream_Io_Lockable
 */

interface Hoa_Stream_Io_Lockable {

    /**
     * Acquire a shared lock (reader).
     *
     * @const int
     */
    const LOCK_SHARED    = LOCK_SH;

    /**
     * Acquire an exclusive lock (writer).
     *
     * @const int
     */
    const LOCK_EXCLUSIVE = LOCK_EX;

    /**
     * Release a lock (shared or exclusive).
     *
     * @const int
     */
    const LOCK_RELEASE   = LOCK_UN;

    /**
     * If we do not want $this->lock() to block while locking.
     *
     * @const int
     */
    const LOCK_NO_BLOCK  = LOCK_NB;



    /**
     * Portable advisory locking.
     * Should take a look at stream_supports_lock().
     *
     * @access  public
     * @param   int     $operation    Operation, use the self::LOCK_* constants.
     * @return  bool
     */
    public function lock ( $operation );
}
