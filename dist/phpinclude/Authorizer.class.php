<?php

require_once "autoload.php";
require_once "Config.class.php";

use \Firebase\JWT\JWT;

class Authorizer {

	public static function authorize() {
		// obtain JWT from request header
		$headers = apache_request_headers();

		if (!isset($headers["Authorization"])) {
			// no authorization header set
			return false;
		}

		$token = $headers["Authorization"];
		if (strlen($token) < 8 || substr($token, 0, 7) !== "Bearer ") {
			// malformed header value
			return false;
		}
		
		$token = substr($token, 7);
		try {
			// decoding the token throws exception when the signature is invalid or the token has expired
			JWT::decode($token, Config::getJWTKey(), array("HS256"));

			// if everything works the user is authorized
			return true;
		} catch (Exception $e) {
			// TODO: for logging purposes it's maybe better to catch exception specifically
			//       `JWT::decode` can throw SignatureInvalidException, BeforeValidException,
			//       ExpiredException or UnexpectedValueException
			return false;
		}
	}

}
