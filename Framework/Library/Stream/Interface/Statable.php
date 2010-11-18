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
 * @subpackage  Hoa_Stream_Interface_Statable
 *
 */

/**
 * Hoa_Core
 */
require_once 'Core.php';

/**
 * Interface Hoa_Stream_Interface_Statable.
 *
 * Interface for statable input/output.
 *
 * @author      Ivan ENDERLIN <ivan.enderlin@hoa-project.net>
 * @copyright   Copyright (c) 2007, 2010 Ivan ENDERLIN.
 * @license     http://gnu.org/licenses/gpl.txt GNU GPL
 * @since       PHP 5
 * @version     0.1
 * @package     Hoa_Stream
 * @subpackage  Hoa_Stream_Interface_Statable
 */

interface Hoa_Stream_Interface_Statable {

    /**
     * Size is undefined.
     *
     * @const int
     */
    const SIZE_UNDEFINED = -1;

    /**
     * Get size.
     *
     * @access  public
     * @return  int
     */
    public function getSize ( );

    /**
     * Get informations about a file.
     *
     * @access  public
     * @return  array
     */
    public function getStatistic ( );

    /**
     * Get last access time of file.
     *
     * @access  public
     * @return  int
     */
    public function getATime ( );

    /**
     * Get inode change time of file.
     *
     * @access  public
     * @return  int
     */
    public function getCTime ( );

    /**
     * Get file modification time.
     *
     * @access  public
     * @return  int
     */
    public function getMTime ( );

    /**
     * Get file group.
     *
     * @access  public
     * @return  int
     */
    public function getGroup ( );

    /**
     * Get file owner.
     *
     * @access  public
     * @return  int
     */
    public function getOwner ( );

    /**
     * Get file permissions.
     *
     * @access  public
     * @return  int
     */
    public function getPermissions ( );

    /**
     * Check if the file is readable.
     *
     * @access  public
     * @return  bool
     */
    public function isReadable ( );

    /**
     * Check if the file is writable.
     *
     * @access  public
     * @return  bool
     */
    public function isWritable ( );

    /**
     * Check if the file is executable.
     *
     * @access  public
     * @return  bool
     */
    public function isExecutable ( );

    /**
     * Clear file status cache.
     *
     * @access  public
     * @return  void
     */
    public function clearStatisticCache ( );

    /**
     * Clear all files status cache.
     *
     * @access  public
     * @return  void
     */
    public static function clearAllStatisticCaches ( );
}
