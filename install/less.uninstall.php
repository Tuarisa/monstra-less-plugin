<?php defined('MONSTRA_ACCESS') or die('No direct script access.');

// Delete Options
Option::delete('less');
Option::delete('less_template');

Table::drop('less-trigger');