<?php

namespace DI\Resolver;

/**
 * Interface ResolverInterface
 * @package DI\Resolver
 */
interface ResolverInterface
{
    /**
     * Resolve dependency by name
     * @param string $name
     * @return mixed
     */
    public function resolve(string $name);
}
