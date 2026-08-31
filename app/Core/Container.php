<?php

namespace App\Core;

use ReflectionClass;
use ReflectionNamedType;

/**
 * Container IoC très simple : instancie une classe en résolvant
 * automatiquement ses dépendances de constructeur (ateliers 15-16).
 */
class Container
{
    private array $bindings = [];

    /**
     * Permet de forcer une implémentation précise pour une interface,
     * ex: $container->bind(ProduitRepositoryInterface::class, ProduitRepository::class);
     */
    public function bind(string $abstract, string $concrete): void
    {
        $this->bindings[$abstract] = $concrete;
    }

    public function make(string $class): object
    {
        $class = $this->bindings[$class] ?? $class;

        $reflection = new ReflectionClass($class);
        $constructor = $reflection->getConstructor();

        if ($constructor === null) {
            return new $class();
        }

        $params = [];
        foreach ($constructor->getParameters() as $param) {
            $type = $param->getType();

            if ($type instanceof ReflectionNamedType && !$type->isBuiltin()) {
                $params[] = $this->make($type->getName());
            } elseif ($param->isDefaultValueAvailable()) {
                $params[] = $param->getDefaultValue();
            } else {
                $params[] = null;
            }
        }

        return $reflection->newInstanceArgs($params);
    }
}
