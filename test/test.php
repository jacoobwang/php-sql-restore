<?php
define('ROOT_DIR', substr(__DIR__, 0, -4));
require ROOT_DIR . 'restore.php';

# 开始执行
$dbObj = new DB();
$dbObj->_executeSQLFile(ROOT_DIR . 'test/test.sql');
