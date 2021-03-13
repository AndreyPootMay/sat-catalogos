<?php

declare(strict_types=1);

namespace PhpCfdi\SatCatalogos;

use PhpCfdi\SatCatalogos\Common\BaseCatalog;
use PhpCfdi\SatCatalogos\Exceptions\SatCatalogosLogicException;

/**
 * Class SatCatalogos
 *
 * @method CFDI\Aduanas                 aduanas();
 * @method CFDI\ClavesUnidades          clavesUnidades();
 * @method CFDI\CodigosPostales         codigosPostales();
 * @method CFDI\FormasDePago            formasDePago();
 * @method CFDI\Impuestos               impuestos();
 * @method CFDI\MetodosDePago           metodosDePago();
 * @method CFDI\Monedas                 monedas();
 * @method CFDI\NumerosPedimentoAduana  numerosPedimentoAduana();
 * @method CFDI\Paises                  paises();
 * @method CFDI\PatentesAduanales       patentesAduanales();
 * @method CFDI\ProductosServicios      productosServicios();
 * @method CFDI\RegimenesFiscales       regimenesFiscales();
 * @method CFDI\ReglasTasaCuota         reglasTasaCuota();
 * @method CFDI\TiposComprobantes       tiposComprobantes();
 * @method CFDI\TiposFactores           tiposFactores();
 * @method CFDI\TiposRelaciones         tiposRelaciones();
 * @method Cfdi\UsosCfdi                usosCFDI();
 * @method Nomina\TiposNominas          tiposNominas();
 * @method Nomina\TiposJornadas         tiposJornadas();
 * @method Nomina\Estados               nEstados();
 * @method Nomina\OrigenesRecursos      nOrigenesRecursos();
 * @method Nomina\Bancos                nBancos();
 * @method Nomina\PeriodicidadesPagos   nPeriodicidadesPagos();
 * @method Nomina\RiesgosPuestos        nRiesgosPuestos();
 * @method Nomina\TiposDeducciones      nTiposDeducciones();
 * @method Nomina\TiposHoras            nTiposHoras();
 * @method Nomina\TiposIncapacidades    nTiposIncapacidades();
 * @method Nomina\TiposOtrosPagos       nTiposOtrosPagos();
 * @method Nomina\TiposPercepciones     nTiposPercepciones();
 * @method Nomina\TiposRegimenes        nTiposRegimenes();
 */
class SatCatalogos
{
    /** @var array<string, mixed> */
    protected $cache;

    /** @var Repository */
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->cache = [];
        $this->repository = $repository;
    }

    /**
     * Magic method to return a catalog using the method name
     *
     * @param string $name
     * @param mixed[] $arguments
     * @return mixed
     * @throws SatCatalogosLogicException if cannot find a matching catalog with the method name
     */
    public function __call(string $name, array $arguments)
    {
        if (isset($this->cache[$name])) {
            return $this->cache[$name];
        }

        /** @var mixed $created */
        $created = $this->create($name);
        if (null !== $created) {
            $this->cache[$name] = $created;
            return $created;
        }

        throw new SatCatalogosLogicException("No se pudo encontrar el catálogo '$name'");
    }

    /**
     * @param string $propertyName
     * @return BaseCatalog|null
     */
    private function create(string $propertyName): ?BaseCatalog
    {
        foreach (['CFDI', 'Nomina'] as $space) {
            $className = '\\' . __NAMESPACE__ . '\\' . $space . '\\' . ucfirst($propertyName);

            if (! class_exists($className)) {
                continue;
            }
            if (! in_array(BaseCatalog::class, class_implements($className) ?: [], true)) {
                continue;
            }
            /** @var BaseCatalog $object */
            $object = new $className();
            $object->withRepository($this->repository);

            return $object;
        }

        return null;
    }
}
