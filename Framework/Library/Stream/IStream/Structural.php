<?php

/**
 * Hoa
 *
 *
 * @license
 *
 * GNU General Public License
 *
 * This file is part of Hoa Open Accessibility.
 * Copyright (c) 2007, 2011 Ivan ENDERLIN. All rights reserved.
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

namespace Hoa\Stream\IStream {

/**
 * Interface \Hoa\Stream\IStream\Structural.
 *
 * Interface for structural input/output.
 *
 * @author     Ivan ENDERLIN <ivan.enderlin@hoa-project.net>
 * @copyright  Copyright (c) 2007, 2011 Ivan ENDERLIN.
 * @license    http://gnu.org/licenses/gpl.txt GNU GPL
 */

interface Structural {

    /**
     * Select root of the document: :root.
     *
     * @access  public
     * @return  \Hoa\Stream\IStream\Structural
     */
    public function selectRoot ( );

    /**
     * Select any elements: *.
     *
     * @access  public
     * @return  array
     */
    public function selectAnyElements ( );

    /**
     * Select elements of type E: E.
     *
     * @access  public
     * @param   string  $E    Element E.
     * @return  array
     */
    public function selectElements ( $E = null );

    /**
     * Select F elements descendant of an E element: E F.
     *
     * @access  public
     * @param   string  $F    Element F.
     * @return  array
     */
    public function selectDescendantElements ( $F = null );

    /**
     * Select F elements children of an E element: E > F.
     *
     * @access  public
     * @param   string  $F    Element F.
     * @return  array
     */
    public function selectChildElements ( $F = null );

    /**
     * Select an F element immediately preceded by an E element: E + F.
     *
     * @access  public
     * @param   string  $F    Element F.
     * @return  \Hoa\Stream\IStream\Structural
     */
    public function selectAdjacentSiblingElement ( $F );

    /**
     * Select F elements preceded by an E element: E ~ F.
     *
     * @access  public
     * @param   string  $F    Element F.
     * @return  array
     */
    public function selectSiblingElements ( $F = null );

    /**
     * Execute a query selector and return the first result.
     *
     * @access  public
     * @param   string  $query    Query.
     * @return  \Hoa\Stream\IStream\Structural
     */
    public function querySelector ( $query );

    /**
     * Execute a query selector and return one or many results.
     *
     * @access  public
     * @param   string  $query    Query.
     * @return  array
     */
    public function querySelectorAll ( $query );
}

}
