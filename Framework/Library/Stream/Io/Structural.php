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
 * @subpackage  Hoa_Stream_Io_Structural
 *
 */

/**
 * Hoa_Framework
 */
require_once 'Framework.php';

/**
 * Interface Hoa_Stream_Io_Structural.
 *
 * Interface for structural input/output.
 *
 * @author      Ivan ENDERLIN <ivan.enderlin@hoa-project.net>
 * @copyright   Copyright (c) 2007, 2010 Ivan ENDERLIN.
 * @license     http://gnu.org/licenses/gpl.txt GNU GPL
 * @since       PHP 5
 * @version     0.1
 * @package     Hoa_Stream
 * @subpackage  Hoa_Stream_Io_Structural
 */

interface Hoa_Stream_Io_Structural {

    /**
     * Select root of the document: :root.
     *
     * @access  public
     * @return  bool
     */
    public function selectRoot ( );

    /**
     * Select any element: *.
     *
     * @access  public
     * @return  bool
     */
    public function selectAnyElement ( );

    /**
     * Select an element of type E: E.
     *
     * @access  public
     * @param   string  $E    Element E.
     * @return  bool
     */
    public function selectElement ( $E );

    /**
     * Select an F element descendant of an E element: E F.
     *
     * @access  public
     * @param   string  $E    Element E.
     * @param   string  $F    Element F.
     * @return  bool
     */
    public function selectDescendantElement ( $E, $F );

    /**
     * Select an F element child of an E element: E > F.
     *
     * @access  public
     * @param   string  $E    Element E.
     * @param   string  $F    Element F.
     * @return  bool
     */
    public function selectChildElement ( $E, $F );

    /**
     * Select an F element immediately preceded by an E element: E + F.
     *
     * @access  public
     * @param   string  $E    Element E.
     * @param   string  $F    Element F.
     * @return  bool
     */
    public function selectAdjacentSiblingElement ( $E, $F );

    /**
     * Select an F element preceded by an E element: E ~ F.
     *
     * @access  public
     * @param   string  $E    Element E.
     * @param   string  $F    Element F.
     * @return  bool
     */
    public function selectSiblingElement ( $E, $F );
}
