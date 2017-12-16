<?php
namespace Helper;

define('ROOT_DIR', dirname(__DIR__, 3));
define('CACHE_DIR', ROOT_DIR.'/var/cache');
define('LOG_DIR', ROOT_DIR.'/var/logs');

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class Unit extends \Codeception\Module
{

}
