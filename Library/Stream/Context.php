<?php

/**
 * Hoa
 *
 *
 * @license
 *
 * New BSD License
 *
 * Copyright © 2007-2012, Ivan Enderlin. All rights reserved.
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

namespace {

from('Hoa')

/**
 * \Hoa\Stream\Exception
 */
-> import('Stream.Exception');

}

namespace Hoa\Stream {

/**
 * Class \Hoa\Stream\Context.
 *
 * Make a multiton of stream contexts.
 *
 * @author     Ivan Enderlin <ivan.enderlin@hoa-project.net>
 * @copyright  Copyright © 2007-2012 Ivan Enderlin.
 * @license    New BSD License
 */

class Context {

    /**
     * Multiton.
     *
     * @var \Hoa\Stream\Context array
     */
    protected static $_instance  = array();

    /**
     * Current ID.
     *
     * @var \Hoa\Stream\Context string
     */
    protected static $_currentId = null;

    /**
     * Context.
     *
     * @var \Hoa\Stream\Context resource
     */
    protected $_context          = null;

    /**
     * Wrapper name.
     *
     * @var \Hoa\Stream\Context string
     */
    protected $_wrapper          = null;



    /**
     * Build a stream context for a specific wrapper.
     *
     * @access  private
     * @param   string   $wrapper    Wrapper name.
     * @throw   \Hoa\Stream\Exception
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
     * @return  \Hoa\Stream\Context
     * @throws  \Hoa\Stream\Exception
     */
    public static function getInstance ( $id = null, $wrapper = null ) {

        if(null === self::$_currentId && null === $id)
            throw new Exception(
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
            $this->getWrapper() => array()
        ));

        return $old;
    }

    /**
     * Set the wrapper value.
     *
     * @access  protected
     * @param   string     $wrapper    Wrapper name.
     * @return  string
     * @throws  \Hoa\Stream\Exception
     */
    protected function setWrapper ( $wrapper ) {

        if(null === $wrapper)
            throw new Exception(
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
     * @return  \Hoa\Context
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
     * @return  \Hoa\Context
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
     * @throws  \Hoa\Stream\Exception
     */
    public function getOption ( $option ) {

        if(false === $this->optionExists($option))
            throw new Exception(
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

}
