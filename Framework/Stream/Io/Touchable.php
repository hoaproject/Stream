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
 * @subpackage  Hoa_Stream_Io_Touchable
 *
 */

/**
 * Hoa_Framework
 */
require_once 'Framework.php';

/**
 * Interface Hoa_Stream_Io_Touchable.
 *
 * Interface for touchable input/output.
 *
 * @author      Ivan ENDERLIN <ivan.enderlin@hoa-project.net>
 * @copyright   Copyright (c) 2007, 2008 Ivan ENDERLIN.
 * @license     http://gnu.org/licenses/gpl.txt GNU GPL
 * @since       PHP 5
 * @version     0.1
 * @package     Hoa_Stream
 * @subpackage  Hoa_Stream_Io_Touchable
 */

interface Hoa_Stream_Io_Touchable {

    /**
     * Set access and modification time of file.
     *
     * @access  public
     * @param   int     $time     Time. If equals to -1, time() should be used.
     * @param   int     $atime    Access time. If equals to -1, $time should be
     *                            used.
     * @return  bool
     */
    public function touch ( $time = -1, $atime = -1 );

    /**
     * Copy file.
     *
     * @access  public
     * @param   string  $to       Destination path.
     * @param   bool    $force    Force to copy if the file $to already exists.
     * @return  bool
     */
    public function copy ( $to );

    /**
     * Move a file.
     *
     * @access  public
     * @param   string  $name     New name.
     * @param   bool    $force    Force to move if the file $name already
     *                            exists.
     * @return  bool
     */
    public function move ( $name );

    /**
     * Delete a file.
     *
     * @access  public
     * @return  bool
     */
    public function delete ( );

    /**
     * Truncate a file to a given length.
     *
     * @access  public
     * @param   int     $size    Size.
     * @return  boom
     */
    public function truncate ( $size );

    /**
     * Change file group.
     *
     * @access  public
     * @param   mixed   $group    Group name or number.
     * @return  bool
     */
    public function changeGroup ( $group );

    /**
     * Change file mode.
     *
     * @access  public
     * @param   int     $mode    Mode (in octal!).
     * @return  bool
     */
    public function changeMode ( $mode );

    /**
     * Change file owner.
     *
     * @access  public
     * @param   string  $user    User.
     * @return  bool
     */
    public function changeOwner ( $user );

    /**
     * Change the current umask.
     *
     * @access  public
     * @param   int     $umask    Umask. If null, given the current umask value.
     * @return  int
     */
    public function umask ( $umask = null );
}
