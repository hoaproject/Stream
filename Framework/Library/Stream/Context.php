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
 * @subpackage  Hoa_Stream_Context
 *
 */

/**
 * Hoa_Stream_Exception
 */
import('Stream.Exception');

/**
 * Class Hoa_Stream_Context.
 *
 * Make a multiton of stream contexts.
 *
 * @author      Ivan ENDERLIN <ivan.enderlin@hoa-project.net>
 * @copyright   Copyright (c) 2007, 2010 Ivan ENDERLIN.
 * @license     http://gnu.org/licenses/gpl.txt GNU GPL
 * @since       PHP 5
 * @version     0.1
 * @package     Hoa_Stream
 * @subpackage  Hoa_Stream_Context
 */

class Hoa_Stream_Context {

    /**
     * Multiton.
     *
     * @var Hoa_Stream_Context array
     */
    protected static $_instance  = array();

    /**
     * Current ID.
     *
     * @var Hoa_Stream_Context string
     */
    protected static $_currentId = null;

    /**
     * Context.
     *
     * @var Hoa_Stream_Context resource
     */
    protected $_context          = null;

    /**
     * Wrapper name.
     *
     * @var Hoa_Stream_Context string
     */
    protected $_wrapper          = null;



    /**
     * Build a stream context for a specific wrapper.
     *
     * @access  private
     * @param   string   $wrapper    Wrapper name.
     * @throw   Hoa_Stream_Exception
     * @return  void
     * @throw   Hoa_Stream_Exception
     */
    protected function __construct ( $wrapper ) {

        $this->setWrapper($wrapper);
        $this->setContext();

        return;
    }

    /**
     * Multiton.
     *
     * @access  public
     * @param   string  $id         Singleton ID.
     * @param   string  $wrapper    Wrapper name.
     * @return  Hoa_Stream_Context
     * @throws  Hoa_Stream_Exception
     */
    public static function getInstance ( $id = null, $wrapper = null ) {

        if(null === self::$_currentId && null === $id)
            throw new Hoa_Stream_Exception(
                'Must precise a singleton index once.', 0);

        if(false === self::contextExists($id))
            self::$_instance[$id] = new self($wrapper);

        if(null !== $id)
            self::$_currentId = $id;

        return self::$_instance[$id];
    }

    /**
     * Create the stream context.
     *
     * @access  protected
     * @return  resource
     */
    protected function setContext ( ) {

        $old            = $this->_context;
        $this->_context = stream_context_create(array(
            strtolower($this->getWrapper()) => array()
        ));

        return $old;
    }

    /**
     * Set the wrapper value.
     *
     * @access  protected
     * @param   string     $wrapper    Wrapper name.
     * @return  string
     */
    protected function setWrapper ( $wrapper ) {

        if(null === $wrapper)
            throw new Hoa_Stream_Exception(
                'Wrapper name cannot be null.', 0);

        $old            = $this->_wrapper;
        $this->_wrapper = strtolower($wrapper);

        return $old;
    }

    /**
     * Add many options to a stream context.
     *
     * @access  public
     * @param   array   $options    Options to add.
     * @return  Hoa_Context
     */
    public function addOptions ( Array $options ) {

        foreach($options as $key => $value)
            $this->addOption($key, $value);

        return $this;
    }

    /**
     * Add an option to a stream context.
     *
     * @access  public
     * @param   string  $key      Key.
     * @param   mixed   $value    Value.
     * @return  Hoa_Context
     */
    public function addOption ( $key, $value ) {

        stream_context_set_option(
            $this->getContext(),
            $this->getWrapper(),
            $key,
            $value
        );

        return $this;
    }

    /**
     * Get current ID.
     *
     * @access  public
     * @return  string
     */
    public function getCurrentId ( ) {

        return self::$_currentId;
    }

    /**
     * Get the stream context.
     *
     * @access  public
     * @return  resource
     */
    public function getContext ( ) {

        return $this->_context;
    }

    /**
     * Get the wrapper value.
     *
     * @access  public
     * @return  string
     */
    public function getWrapper ( ) {

        return $this->_wrapper;
    }

    /**
     * Get stream context options.
     *
     * @access  public
     * @return  array
     */
    public function getOptions ( ) {

        return stream_context_get_options($this->getContext());
    }

    /**
     * Get a specific stream context option.
     *
     * @access  public
     * @param   string  $option    Option name.
     * @return  mixed
     * @throws  Hoa_Stream_Exception
     */
    public function getOption ( $option ) {

        if(false === $this->optionExists($option))
            throw new Hoa_Stream_Exception(
                'Option %s does not exist for the context that wrappes %s, with ' .
                'id %s.',
                1, array($option, $this->getWrapper(), $this->getCurrentId()));

        $options = $this->getOptions();

        return $options[$option];
    }

    /**
     * Check if an option exists.
     *
     * @access  public
     * @param   string  $option    Option name.
     * @return  bool
     */
    public function optionExists ( $option ) {

        return array_key_exists($option, $this->getOptions());
    }

    /**
     * Check if a context exists.
     *
     * @access  public
     * @param   string  $id    Context ID.
     * @return  bool
     */
    public static function contextExists ( $id ) {

        return isset(self::$_instance[$id]);
    }
}
