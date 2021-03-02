<?php

namespace DI\Resolver;

use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionException;

class Resolver implements ResolverInterface
{
    /** @var ContainerInterface */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $name
     * @return mixed
     * @throws ReflectionException
     * @throws ResolverException
     */
    public function __invoke(string $name)
    {
        return $this->resolve($name);
    }

    /**
     * @param string $name
     * @return mixed
     * @throws ReflectionException
     * @throws ResolverException
     */
    public function resolve(string $name)
    {
        if ($this->container->has($name)) {
            return $this->container->get($name);
        }

        $reflection = new ReflectionClass($name);

        $constructor = $reflection->getConstructor();

        if ($constructor === null) {
            return $reflection->newInstance();
        }

        $args = [];
        foreach ($constructor->getParameters() as $arg) {
            $class = $arg->getClass();

            if ($class === null) {
                throw new ResolverException('Argument type is required');
            }

            $args[] = $this->container->get($class->getName());
        }

        return $reflection->newInstanceArgs($args);
    }
}
