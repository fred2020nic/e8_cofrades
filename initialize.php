<?php

$host = $_SERVER['HTTP_HOST'];
//  var_dump($host);

 if ($host === 'localhost' ) {


$dev_data = array('id' => '-1', 'firstname' => 'Mauricio', 'lastname' => 'Sevilla', 'username' => 'configuroweb', 'password' => '4b67deeb9aba04a5b54632ad19934f26', 'last_login' => '', 'date_updated' => '', 'date_added' => '');
if (!defined('base_url')) define('base_url', 'http://localhost/0.1_cw/4.e8/e8_cofrades/');
if (!defined('base_app')) define('base_app', str_replace('\\', '/', __DIR__) . '/');
if (!defined('dev_data')) define('dev_data', $dev_data);
if (!defined('DB_SERVER')) define('DB_SERVER', "localhost");
if (!defined('DB_USERNAME')) define('DB_USERNAME', "root");
if (!defined('DB_PASSWORD')) define('DB_PASSWORD', "");
if (!defined('DB_NAME')) define('DB_NAME', "cofrades");

}else{

    // prueba de deploy por git 15-05-2024 - 15:32



    $dev_data = array('id' => '-1', 'firstname' => 'Mauricio', 'lastname' => 'Sevilla', 'username' => 'configuroweb', 'password' => '4b67deeb9aba04a5b54632ad19934f26', 'last_login' => '', 'date_updated' => '', 'date_added' => '');
    if (!defined('base_url')) define('base_url', 'https://fepapp.com/e8_cofrades/');
    if (!defined('base_app')) define('base_app', str_replace('\\', '/', __DIR__) . '/');
    if (!defined('dev_data')) define('dev_data', $dev_data);
    if (!defined('DB_SERVER')) define('DB_SERVER', "localhost");
    if (!defined('DB_USERNAME')) define('DB_USERNAME', "u363832898_e8_cofrades");
    if (!defined('DB_PASSWORD')) define('DB_PASSWORD', "#1234Abcd..#");
    if (!defined('DB_NAME')) define('DB_NAME', "u363832898_e8_cofrades");


}