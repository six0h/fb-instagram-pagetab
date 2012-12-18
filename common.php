<?php

require_once(BASE_PATH . 'functions.php');

require_once(CLASS_PATH . 'Database.class.php');
require_once(CLASS_PATH . 'BaseUser.class.php');
require_once(CLASS_PATH . 'MongoSessionHandler.class.php');

MongoSessionHandler::register(DB_NAME, 'sessions');
$session = MongoSessionHandler::getInstance();

$db = Database::getInstance();

?>

