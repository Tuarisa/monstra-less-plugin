<?php defined('MONSTRA_ACCESS') or die('No direct script access.');

// Add New Options
Option::add('less', 'less test value');
Option::add('less_template', 'index');

Table::create('less-trigger', array('object', 'subject'));