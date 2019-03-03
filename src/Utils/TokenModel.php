<?php
/**
 * Created by PhpStorm.
 * User: Etienne
 * Date: 25/02/2019
 * Time: 22:53
 */

namespace App\Utils;


class TokenModel
{
    /**
     * @return string
     * @throws \Exception
     */
    public function generateToken(): string
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }
}
