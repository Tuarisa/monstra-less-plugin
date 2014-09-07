<?php

/**
 *  Less plugin
 *
 *  @package Monstra
 *  @subpackage Plugins
 *  @author Romanenko Sergey / Awilum
 *  @copyright 2012-2014 Romanenko Sergey / Awilum
 *  @version 1.0.0
 *
 */

// Register plugin
Plugin::register( __FILE__,
                __('Less', 'less'),
                __('Less plugin for Monstra', 'less'),
                '1.0.0',
                'Awilum',
                'http://monstra.org/',
                'less');

// Load Less Admin for Editor and Admin
if (Session::exists('user_role') && in_array(Session::get('user_role'), array('admin', 'editor'))) {

    Plugin::admin('less');

}


/**
 * Less class
 */
class Less extends Frontend
{
    /**
     * Less main function
     */
    public static function main()
    {
        // Do something...
    }

    /**
     * Set Less title
     */
    public static function title()
    {
        return 'Less title';
    }

    /**
     * Set Less keywords
     */
    public static function keywords()
    {
        return 'Less keywords';
    }

    /**
     * Set Less description
     */
    public static function description()
    {
        return 'Less description';
    }

    /**
     * Set Less content
     */
    public static function content()
    {
        return 'Less content';
    }

    /**
     * Set Less template
     */
    public static function template()
    {
        return 'index';
    }
}
