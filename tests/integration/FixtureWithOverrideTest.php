<?php
/**
 * PHP version 7.1
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace ComPHPPuebla\Fixtures;

use ComPHPPuebla\Fixtures\Database\DBALConnection;
use PHPUnit\Framework\TestCase;

class FixtureWithOverrideTest extends TestCase
{
    use ProvidesConnections;

    /**
     * @test
     * @dataProvider databaseConnections
     */
    function it_loads_a_fixture_with_override_data(ConnectionFactory $factory)
    {
        $overrideData = [
            '[name_1]' => 'CASMEN GASOL',
            '[name_2]' => 'COMBUSTIBLES JV',
        ];

        $connection = $factory->connect();
        $fixtures = new Fixture(new DBALConnection($connection));
        $database = new TestDatabase($connection);

        $fixtures->loadWithOverride(
            "$this->path/fixture-with-override-aliases.yml",
            $overrideData
        );

        $station1 = $database->findStationNamed('CASMEN GASOL');
        $station2 = $database->findStationNamed('COMBUSTIBLES JV');

        // Stations have been saved
        $this->assertGreaterThan(0, $station1['station_id']);
        $this->assertGreaterThan(0, $station2['station_id']);
    }

    /** @before */
    protected function configureFixtures(): void
    {
        $this->path = __DIR__ . '/../../data/';
    }

    /** @var string */
    private $path;
}
