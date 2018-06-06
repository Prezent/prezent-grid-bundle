<?php

namespace Prezent\GridBundle\Tests\Functional;

/**
 * @author Sander Marechal
 */
class GridBundleTest extends WebTestCase
{
    /**
     * @group legacy
     */
    public function testGrid()
    {
        $client = self::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // A grid of 2 rows, 2 columns
        $this->assertCount(2, $crawler->filter('thead th'));
        $this->assertCount(2, $crawler->filter('tbody tr'));

        // Translations work
        $this->assertCount(1, $crawler->filter('th:contains("ID")'));

        // Sorting works
        $this->assertCount(1, $crawler->filter('th a:contains("Name")'));
        $this->assertCount(1, $crawler->filter('th a[href*="sort_by"]'));

        // Routing works
        $this->assertCount(2, $crawler->filter('tbody td a[href*="view"]'));
    }
}
