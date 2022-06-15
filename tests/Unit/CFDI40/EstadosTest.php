<?php

declare(strict_types=1);

namespace PhpCfdi\SatCatalogos\Tests\Unit\CFDI40;

use PhpCfdi\SatCatalogos\CFDI40\Estados;
use PHPUnit\Framework\TestCase;

final class EstadosTest extends TestCase
{
    /** @var array<string, mixed> */
    protected $validRow = [
        'estado' => 'MOR',
        'pais' => 'MEX',
        'texto' => 'Morelos',
        'vigencia_desde' => '2000-01-01',
        'vigencia_hasta' => '',
    ];

    public function testCreate(): void
    {
        $estados = new Estados();
        $created = $estados->createEstado($this->validRow);

        $this->assertSame($created->codigo(), $this->validRow['estado']);
        $this->assertSame($created->pais(), $this->validRow['pais']);
        $this->assertSame($created->texto(), $this->validRow['texto']);
        $this->assertSame($created->vigenteDesde(), strtotime($this->validRow['vigencia_desde']));
        $this->assertSame($created->vigenteHasta(), 0);
    }
}
