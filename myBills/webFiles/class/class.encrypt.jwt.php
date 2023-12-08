<?php
require_once('libs/php-jwt/src/JWK.php');
require_once('libs/php-jwt/src/JWT.php');
require_once('libs/php-jwt/src/ExpiredException.php');
require_once('libs/php-jwt/src/BeforeValidException.php');
require_once('libs/php-jwt/src/SignatureInvalidException.php');

use \Firebase\JWT\JWT;

class EncryptJWT {
	protected $key="Int3gr@4W.W.W.";
	public function __construct() {
	}
	public function encode($params=array()) {
		if (!isset($params['expiration'])) {
			$params['expiration']=datetimes_to_sql('+1day time_begin');
		}
		if (!isset($params['creation'])) {
			$params['creation']=datetimes_to_sql('now');
		}
		if (!isset($params['user_id'])) {
			$params['user_id']=current_user_id();
		}
		return JWT::encode($params, $this->key,'HS256');
	}
	public function decode($params) {
		return JWT::decode($params, $this->key, array('HS256'));
	}
}
?>