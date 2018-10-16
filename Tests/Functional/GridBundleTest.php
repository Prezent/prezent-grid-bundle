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

        //Test sortable rendering and interaction
        $crawler = $client->request('GET', '/?sort_by=name');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $activeColumns = $crawler->filter('thead th a[data-sort-dir="asc"]');
        $this->assertCount(1, $activeColumns);

        $crawler = $client->click($activeColumns->first()->link());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertCount(1, $crawler->filter('thead th a[data-sort-dir="desc"]'));
    }
}
