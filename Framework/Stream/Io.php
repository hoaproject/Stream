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
 * @subpackage  Hoa_Stream_Io
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
 * Interface Hoa_Stream_Io.
 *
 * Interface for input/output.
 *
 * @author      Ivan ENDERLIN <ivan.enderlin@hoa-project.net>
 * @copyright   Copyright (c) 2007, 2008 Ivan ENDERLIN.
 * @license     http://gnu.org/licenses/gpl.txt GNU GPL
 * @since       PHP 5
 * @version     0.1
 * @package     Hoa_Stream
 * @subpackage  Hoa_Stream_Io
 */

interface Hoa_Stream_Io {

    /**
     * Read n characters.
     *
     * @access  public
     * @param   int     $length    Length.
     * @return  string
     */
    public function read ( $length );

    /**
     * Alias of $this->read().
     *
     * @access  public
     * @param   int     $length    Length.
     * @return  string
     */
    public function readString ( $length );

    /**
     * Read a char.
     * It could be equivalent to $this->read(1).
     *
     * @access  public
     * @return  string
     */
    public function readChar ( );

    /**
     * Read an integer.
     *
     * @access  public
     * @param   int     $length    Length.
     * @return  int
     */
    public function readInteger ( $length = 1 );

    /**
     * Read a float.
     *
     * @access  public
     * @param   int     $length    Length.
     * @return  float
     */
    public function readFloat ( $length = 1 );

    /**
     * Read a line.
     *
     * @access  public
     * @return  string
     */
    public function readLine ( );

    /**
     * Read all, i.e. read as much as possible.
     *
     * @access  public
     * @return  string
     */
    public function readAll ( );

    /**
     * Write n characters.
     *
     * @access  public
     * @param   string  $string    String.
     * @return  int
     */
    public function write ( $string );

    /**
     * Alias of $this->write().
     *
     * @access  public
     * @param   string  $string    String.
     * @return  int
     */
    public function writeString ( $string );

    /**
     * Write a character.
     * It could be equivalent to $this->write(1).
     *
     * @access  public
     * @param   string  $char    Character.
     * @return  int
     */
    public function writeChar ( $char );

    /**
     * Write an integer.
     *
     * @access  public
     * @param   int     $integer    Integer.
     * @return  int
     */
    public function writeInteger ( $length = 1 );

    /**
     * Write a float.
     *
     * @access  public
     * @param   float   $float    Float.
     * @return  int
     */
    public function writeFloat ( $length = 1 );

    /**
     * Write a line.
     *
     * @access  public
     * @param   string  $line    Line.
     * @return  int
     */
    public function writeLine ( );

    /**
     * Write all, i.e. as much as possible.
     *
     * @access  public
     * @param   string  $string    String.
     * @return  int
     */
    public function writeAll ( );

    /**
     * Parse input from a file according to a format.
     *
     * @access  public
     * @param   string  $format    Format (see printf's formats).
     * @return  array
     */
    public function scanf ( $format );

    /**
     * Test for end-of-file.
     *
     * @access  public
     * @return  bool
     */
    public function eof ( );

    /**
     * Get filename component of path.
     *
     * @access  public
     * @return  string
     */
    public function basename ( );

    /**
     * Get directory name component of path.
     *
     * @access  public
     * @return  string
     */
    public function dirname ( );

    /**
     * Get size.
     *
     * @access  public
     * @return  int
     */
    public function size ( );
}
