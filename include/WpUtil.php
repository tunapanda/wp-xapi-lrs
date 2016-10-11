<?php

namespace xapilrs;

use \PDO;

/**
 * Wordpress utils.
 */
if (!class_exists("xapilrs\\WpUtil")) {
	class WpUtil {

		/**
		 * Bootstrap from inside a plugin.
		 */
		public static function getWpLoadPath() {
			if (php_sapi_name()=="cli")
				$path=$_SERVER["PWD"];

			else
				$path=$_SERVER['SCRIPT_FILENAME'];

			while (1) {
				if (file_exists($path."/wp-load.php"))
					return $path."/wp-load.php";

				$last=$path;
				$path=dirname($path);

				if ($last==$path)
					throw new \Exception("Not inside a wordpress install.");
			}
		}

		/**
		 * Create a PDO object that is compatible with the current
		 * wordpress install.
		 */
		public static function getCompatiblePdo() {
			static $pdo;

			if (!$pdo) {
				$pdo=new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME,DB_USER,DB_PASSWORD);
				$pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,TRUE);
			}

			return $pdo;
		}
	}
}