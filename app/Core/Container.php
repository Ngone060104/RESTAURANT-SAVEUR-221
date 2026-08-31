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
    private array $factories = [];
    private array $singletons = [];

    /**
     * Permet de forcer une implémentation précise pour une interface,
     * ex: $container->bind(ProduitRepositoryInterface::class, ProduitRepository::class);
     */
    public function bind(string $abstract, string $concrete): void
    {
        $this->bindings[$abstract] = $concrete;
    }

    /**
     * Permet de dire au container COMMENT construire une dépendance
     * qu'il ne peut pas deviner tout seul (ex: PDO, qui a besoin d'un
     * DSN). $factory est appelée une seule fois, le résultat est
     * ensuite mis en cache et réutilisé (comportement Singleton).
     *
     * ex: $container->singleton(PDO::class, fn () => Database::getInstance()->getConnection());
     */
    public function singleton(string $abstract, callable $factory): void
    {
        $this->factories[$abstract] = $factory;
    }

    public function make(string $class): object
    {
        if (isset($this->singletons[$class])) {
            return $this->singletons[$class];
        }

        if (isset($this->factories[$class])) {
            return $this->singletons[$class] = ($this->factories[$class])();
        }

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
