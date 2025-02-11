<?php
/*
Plugin Name: Books
Description: My first plugin.
Version: 1.0.0
Author: Konika Nahar
Text Domain: kn-books
*/

namespace KN\BookPlugin;

const TEXT_DOMAIN = 'kn-books';
const PLUGIN_FILE = __FILE__;

//include classes
require_once "classes/Singleton.php";
require_once "classes/Plugin.php";
require_once "classes/BookPostType.php";
require_once "classes/BookGenre.php";
require_once "classes/BookMeta.php";
require_once "classes/ReviewPostType.php";
require_once "classes/ReviewMeta.php";
require_once "classes/ReviewShortcode.php";
require_once "classes/RecentBooksShortcode.php";
require_once "classes/BookSettings.php";


Plugin::getInstance();
