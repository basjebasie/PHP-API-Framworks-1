<?php
require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Routing\RouteContext;
use \Firebase\JWT\JWT;

class jwt_token{

    public function createToken($name)
    {
        $base64UrlHeader = $this->base64UrlEncode(json_encode(["alg" => "HS256", "typ" => "JWT"]));
        $base64UrlPayload = $this->base64UrlEncode(json_encode(["name" => $name, "exp" => time() + 3600, "iat" => time()]));
        $base64UrlSignature = hash_hmac('sha256', $base64UrlHeader . '.' . $base64UrlPayload, "secretKey", true);
        $base64UrlSignature = $this->base64UrlEncode($base64UrlSignature);
        return $base64UrlHeader . '.' . $base64UrlPayload . '.' . $base64UrlSignature;
    }

    private function base64UrlEncode($data)
    {
        $base64 = base64_encode($data);
        $base64Url = strtr($base64, '+/', '-_');
        return rtrim($base64Url, '=');
    }

    private function base64UrlDecode($data)
    {
        $base64 = strtr($data, '-_', '+/');
        $base64Padded = str_pad($base64, strlen($base64) % 4, '=', STR_PAD_RIGHT);
        return base64_decode($base64Padded);
    }

    public function validateToken($token)
    {
        list($base64UrlHeader, $base64UrlPayload, $base64UrlSignature) = explode('.', $token);

        $signature = $this->base64UrlDecode($base64UrlSignature);
        $expectedSignature = hash_hmac('sha256', $base64UrlHeader . '.' . $base64UrlPayload, "secretKey", true);

        return hash_equals($signature, $expectedSignature);
    }
}

