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
 * @subpackage  Hoa_Stream_Composite
 *
 */

/**
 * Hoa_Framework
 */
require_once 'Framework.php';

/**
 * Class Hoa_Stream_Composite.
 *
 * Declare a composite stream, i.e. a stream that use stream.
 *
 * @author      Ivan ENDERLIN <ivan.enderlin@hoa-project.net>
 * @copyright   Copyright (c) 2007, 2010 Ivan ENDERLIN.
 * @license     http://gnu.org/licenses/gpl.txt GNU GPL
 * @since       PHP 5
 * @version     0.1
 * @package     Hoa_Stream
 * @subpackage  Hoa_Stream_Composite
 */

abstract class Hoa_Stream_Composite {

    /**
     * Current stream.
     *
     * @var mixed object
     */
    protected $_stream      = null;

    /**
     * Inner stream.
     *
     * @var Hoa_Stream object
     */
    protected $_innerStream = null;



    /**
     * Set current stream.
     *
     * @access  protected
     * @param   object  $stream    Current stream.
     * @return  object
     */
    protected function setStream ( $stream ) {

        $old           = $this->_stream;
        $this->_stream = $stream;

        return $old;
    }

    /**
     * Get current stream.
     *
     * @access  protected
     * @return  object
     */
    protected function getStream ( ) {

        return $this->_stream;
    }

    /**
     * Set inner stream.
     *
     * @access  protected
     * @param   Hoa_Stream  $innerStream    Inner stream.
     * @return  Hoa_Stream
     */
    protected function setInnerStream ( Hoa_Stream $innerStream ) {

        $old                = $this->_innerStream;
        $this->_innerStream = $innerStream;

        return $old;
    }

    /**
     * Get inner stream.
     *
     * @access  protected
     * @return  Hoa_Stream
     */
    protected function getInnerStream ( ) {

        return $this->_innerStream;
    }
}
