<?php 

defined('ROOTPATH') OR exit('Access Denied!');

if($_SERVER['SERVER_NAME'] == 'localhost')
{
	/** localhost database config **/
	define('DBNAME', 'memeworld_db');
	define('DBHOST', 'localhost');
	define('DBUSER', 'root');
	define('DBPASS', '');
	define('DBDRIVER', '');
	
	define('ROOT', 'http://localhost/MemeWorld/public');

}else
{
	/** online server database config **/
	define('DBNAME', 'my_db');
	define('DBHOST', 'localhost');
	define('DBUSER', 'root');
	define('DBPASS', '');
	define('DBDRIVER', '');

	define('ROOT', 'https://www.yourwebsite.com');

}

define('APP_NAME', "Meme World");
define('APP_DESC', "Best social website on the planet");

/** true means show errors **/
define('DEBUG', true);
