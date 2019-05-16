<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RoutesTest extends WebTestCase
{
    /**
     * @dataProvider urlProvider
     */
    public function testPageIsSuccessful($url)
    {
        $client = self::createClient([], [
            'PHP_AUTH_USER' => 'admin@admin.com',
            'PHP_AUTH_PW'   => 'admin',
        ]);
        $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful(), $client->getResponse()->getStatusCode());
    }

    public function urlProvider()
    {
        yield ['/'];
        yield ['/project/'];
        yield ['/project/type/'];
        yield ['/user/my-profil'];
    }
}
