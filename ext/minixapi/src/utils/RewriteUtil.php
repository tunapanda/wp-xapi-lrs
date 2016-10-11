<?php

/**
 * Util for use with apache mod_rewrite.
 */
class RewriteUtil {

	/**
	 * Get part of the url path that specifies the script to invoke.
	 *
	 * For example, say that our web root is at `/var/www/html`, and we have
	 * an `index.php` file at `/var/www/html/some/site/index.php`, and we
	 * have a `.htaccess` file at `/var/www/html/some/site/.htaccess` that
	 * makes the `index.php` file catch all requests. Say now that we get
	 * a request to the url:
	 *
	 * http://localhost/some/site/and/some/info
	 *
	 * If we call this function from inside our `index.php` file, then 
	 * it would return `/some/site/` since that is the part of the
	 * url that actually leads to the invocation of our script.
	 */
	public static function getBase() {
		$pathinfo=pathinfo($_SERVER["SCRIPT_NAME"]);
		$dirname=$pathinfo["dirname"];
		$url=$_SERVER["REQUEST_URI"];
		//Logger::debug("url: ".$url);
		if (strpos($url,"?")!==FALSE)
			$url=substr($url,0,strpos($url,"?"));
		//Logger::debug("url: ".$url);
		if (substr($url,0,strlen($dirname))!=$dirname)
			throw new Exception("Somthing is malformed.");
		$s=substr($url,0,strlen($dirname))."/";
		return str_replace("//","/",$s);
	}

	/**
	 * Get base including host and protocol.
	 *
	 * Same as {@link getBase} but includes the protocol and server also.
	 */
	public static function getBaseUrl() {
		$url="http://";
		if (isset($_SERVER['HTTPS']))
			$url="https://";
		$url.=$_SERVER["SERVER_NAME"];
		if ($_SERVER["SERVER_PORT"]!=80)
			$url.=":".$_SERVER["SERVER_PORT"];
		$url.=self::getBase();
		return $url;
	}

	/**
	 * Get component of the path that comes after the location
	 * of the script.
	 *
	 * For example, say that our web root is at `/var/www/html`, and we have
	 * an `index.php` file at `/var/www/html/some/site/index.php`, and we
	 * have a `.htaccess` file at `/var/www/html/some/site/.htaccess` that
	 * makes the `index.php` file catch all requests. Say now that we get
	 * a request to the url:
	 *
	 * http://localhost/some/site/and/some/info
	 *
	 * If we call this function from inside our `index.php` file, then 
	 * it would return `and/some/info` since that is the part of the
	 * url that comes after the location of the script and is probably
	 * interesting to us as some sort of parameter.
	 *
	 * If the url starts with the full script name, assume we are note redirected
	 * so take what comes after the script instead.
	 */
	public static function getPath() {
		$scriptPart=substr(
			$_SERVER["REQUEST_URI"],
			0,
			strlen($_SERVER["SCRIPT_NAME"])
		);

		if ($scriptPart==$_SERVER["SCRIPT_NAME"]) {
			$after=substr($_SERVER["REQUEST_URI"],strlen($_SERVER["SCRIPT_NAME"]));
			if (strpos($after,"?")!==FALSE)
				$after=substr($after,0,strpos($after,"?"));

			return $after;
		}

		$pathinfo=pathinfo($_SERVER["SCRIPT_NAME"]);
		$dirname=$pathinfo["dirname"];
		$url=$_SERVER["REQUEST_URI"];
		if (strpos($url,"?")!==FALSE)
			$url=substr($url,0,strpos($url,"?"));
		if (substr($url,0,strlen($dirname))!=$dirname)
			throw new Exception("Somthing is malformed.");

		return substr($url,strlen($dirname));
	}

	/**
	 * Get path components.
	 *
	 * Same as {@link getPath} but also splits the path into components.
	 */
	public static function getPathComponents() {
		return self::splitUrlPath(self::getPath());
	}

	/**
	 * Split url path.
	 *
	 * Split a path like `////some///path///` into the components
	 * `some` and `path`. I.e. The path will be split with respect to /,
	 * but empty strings will be ignored.
	 */
	public static function splitUrlPath($path) {
		$components=explode("/",$path);
		$res=array();
		foreach ($components as $component)
			if ($component)
				$res[]=$component;
		return $res;
	}
}