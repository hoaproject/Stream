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

namespace Hoa\Stream {

/**
 * Class \Hoa\Stream\Composite.
 *
 * Declare a composite stream, i.e. a stream that use stream.
 *
 * @author     Ivan ENDERLIN <ivan.enderlin@hoa-project.net>
 * @copyright  Copyright (c) 2007, 2010 Ivan ENDERLIN.
 * @license    http://gnu.org/licenses/gpl.txt GNU GPL
 */

abstract class Composite {

    /**
     * Current stream.
     *
     * @var mixed object
     */
    protected $_stream      = null;

    /**
     * Inner stream.
     *
     * @var \Hoa\Stream object
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
     * @param   \Hoa\Stream  $innerStream    Inner stream.
     * @return  \Hoa\Stream
     */
    protected function setInnerStream ( \Hoa\Stream $innerStream ) {

        $old                = $this->_innerStream;
        $this->_innerStream = $innerStream;

        return $old;
    }

    /**
     * Get inner stream.
     *
     * @access  protected
     * @return  \Hoa\Stream
     */
    protected function getInnerStream ( ) {

        return $this->_innerStream;
    }
}

}
