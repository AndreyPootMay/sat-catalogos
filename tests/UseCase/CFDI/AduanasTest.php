<?php

declare(strict_types=1);

namespace PhpCfdi\SatCatalogos\Tests\UseCase\CFDI;

use PhpCfdi\SatCatalogos\CFDI\Aduanas;
use PhpCfdi\SatCatalogos\Exceptions\SatCatalogosNotFoundException;
use PhpCfdi\SatCatalogos\Repository;
use PhpCfdi\SatCatalogos\Tests\UsingTestingDatabaseTestCase;

class AduanasTest extends UsingTestingDatabaseTestCase
{
    /** @var Aduanas */
    protected $aduanas;

    protected function setUp()
    {
        parent::setUp();
        $this->aduanas = new Aduanas($this->getRepository());
    }

    public function testObtainExistentEntry()
    {
        $aduana = $this->aduanas->obtain('24');

        $this->assertSame('24', $aduana->id());
        $this->assertContains('NUEVO LAREDO', $aduana->texto());
        $this->assertSame('2017-01-01', date('Y-m-d', $aduana->vigenteDesde()));
        $this->assertSame(0, $aduana->vigenteHasta());
    }

    public function testObtainNonExistentEntry()
    {
        $this->expectException(SatCatalogosNotFoundException::class);
        $this->expectExceptionMessage(Repository::CFDI_ADUANAS);
        $this->aduanas->obtain('foo');
    }

    public function testEntryExists()
    {
        $this->assertTrue($this->aduanas->exists('24'));
        $this->assertFalse($this->aduanas->exists('foo'));
    }
}