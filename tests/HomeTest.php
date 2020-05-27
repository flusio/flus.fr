<?php

namespace Website;

class HomeTest extends \PHPUnit\Framework\TestCase
{
    use \Minz\Tests\InitializerHelper;
    use \Minz\Tests\ApplicationHelper;
    use \Minz\Tests\ResponseAsserts;

    public function testIndexRendersCorrectly()
    {
        $response = $this->appRun('GET', '/');

        $this->assertResponse($response, 200, 'Flus, média social citoyen');
        $this->assertPointer($response, 'home/index.phtml');
    }

    public function testCreditsRendersCorrectly()
    {
        $response = $this->appRun('GET', '/credits');

        $this->assertResponse($response, 200, 'Crédits');
        $this->assertPointer($response, 'home/credits.phtml');
    }

    public function testLegalRendersCorrectly()
    {
        $response = $this->appRun('GET', '/mentions-legales');

        $this->assertResponse($response, 200, 'Mentions légales');
        $this->assertPointer($response, 'home/legal.phtml');
    }

    public function testCgvRendersCorrectly()
    {
        $response = $this->appRun('GET', '/cgv');

        $this->assertResponse($response, 200, 'Conditions Générales de Vente');
        $this->assertPointer($response, 'home/cgv.phtml');
    }

    public function testFundingRendersCorrectly()
    {
        $response = $this->appRun('GET', '/financement');

        $this->assertResponse($response, 200);
        $this->assertPointer($response, 'home/funding.phtml');
    }

    public function testRobotsRendersCorrectly()
    {
        $response = $this->appRun('GET', '/robots.txt');

        $this->assertResponse($response, 200);
    }

    public function testSitemapRendersCorrectly()
    {
        $response = $this->appRun('GET', '/sitemap.xml');

        $this->assertResponse($response, 200);
    }
}
