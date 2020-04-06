<?php

namespace Website\controllers\home;

use Minz\Tests\IntegrationTestCase;

// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
class homeTest extends IntegrationTestCase
{
    public function testIndex()
    {
        $request = new \Minz\Request('GET', '/');

        $response = self::$application->run($request);

        $this->assertResponse($response, 200, 'Bonjour le monde&nbsp;!');
        $pointer = $response->output()->pointer();
        $this->assertSame('home/index.phtml', $pointer);
    }
}
