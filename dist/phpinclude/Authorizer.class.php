<?php

require_once "autoload.php";
require_once "Config.class.php";

use \Firebase\JWT\JWT;


class Authorizer {

	const JWT_VALID = 0;
	const JWT_EMPTY = 1;
	const JWT_INVALID = 2;
	const JWT_EXPIRED = 3;

	public static function authorize() {
		// obtain JWT from request header
		$headers = apache_request_headers();

		if (!isset($headers["Authorization"])) {
			// no authorization header set
			return self::JWT_EMPTY;
		}

		$token = $headers["Authorization"];
		if (strlen($token) < 8 || substr($token, 0, 7) !== "Bearer ") {
			// malformed header value
			return self::JWT_INVALID;
		}
		
		$token = substr($token, 7);
		try {
			// decoding the token throws exception when the signature is invalid or the token has expired
			JWT::decode($token, Config::getJWTKey(), array("HS256"));

			// if everything works the user is authorized
			return self::JWT_VALID;
		} catch (UnexpectedValueException | SignatureInvalidException $e) {
			// TODO: for logging purposes it's maybe better to catch exception specifically
			//       `JWT::decode` can throw SignatureInvalidException, BeforeValidException,
			//       ExpiredException or UnexpectedValueException
			return self::JWT_INVALID;
		} catch (ExpiredException $e) {
			return self::JWT_EXPIRED;
		}
	}

}
