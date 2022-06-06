<?php

declare(strict_types=1);

namespace PhpCfdi\SatCatalogos\Tests\Unit\CFDI40;

use PhpCfdi\SatCatalogos\CFDI40\Exportaciones;
use PhpCfdi\SatCatalogos\Repository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class ExportacionesTest extends TestCase
{
    /** @var array<string, mixed> */
    protected $validRow = [
        'id' => '0000',
        'texto' => '0000',
        'vigencia_desde' => '2000-01-01',
        'vigencia_hasta' => '',
    ];

    public function testObtainWithMock(): void
    {
        /** @var Repository&MockObject $repository */
        $repository = $this->createMock(Repository::class);
        $repository->method('queryById')->willReturn($this->validRow);

        $exportaciones = new Exportaciones();
        $exportaciones->withRepository($repository);

        $patenteAduanal = $exportaciones->obtain('0000');
        $this->assertStringContainsString('0000', $patenteAduanal->texto());
    }

    public function testCreate(): void
    {
        $exportaciones = new Exportaciones();
        $created = $exportaciones->create($this->validRow);

        $this->assertSame($created->id(), $this->validRow['id']);
        $this->assertSame($created->texto(), $this->validRow['texto']);
        $this->assertSame($created->vigenteDesde(), strtotime($this->validRow['vigencia_desde']));
        $this->assertSame($created->vigenteHasta(), 0);
    }
}