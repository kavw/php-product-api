<?php

declare(strict_types=1);

namespace App\Infra\Routing;

use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Serializer\SerializerInterface;

final class Dispatcher implements DispatcherInterface
{
    private ?RouteCollection $routes = null;

    /**
     * @var array<string, ActionInterface>
     */
    private array $map = [];

    public function __construct(
        /**
         * @var ActionInterface[]
         */
        private readonly iterable $actions,
        private readonly RequestContextFactory $contextFactory,
    ) {
    }

    public function dispatch(ServerRequestInterface $request): DispatchResult
    {
        $context = $this->contextFactory->createFromRequest($request);
        $res = (new UrlMatcher($this->getRoutes(), $context))
            ->match($context->getPathInfo());

        if (!isset($this->map[$res['_route']])) {
            throw new \RuntimeException(
                sprintf("Can't find action for the route '%s", $res['_route'])
            );
        }

        $action = $this->map[$res['_route']];
        unset($res['_route']);

        return new DispatchResult(
            $action,
            $this->processArguments(
                $action->getRoute()->arguments,
                $res
            )
        );
    }

    private function getRoutes(): RouteCollection
    {
        if ($this->routes !== null) {
            return $this->routes;
        }

        $this->routes = new RouteCollection();
        foreach ($this->actions as $action) {
            $rote = $action->getRoute();
            $this->map[$rote->name] = $action;
            $this->routes->add(
                $rote->name,
                new \Symfony\Component\Routing\Route(
                    path: $rote->path,
                    methods: $rote->method->value
                )
            );
        }
        return $this->routes;
    }

    /**
     * @param list<string> $declared
     * @param array<string, string> $incoming
     * @return list<string>
     */
    private function processArguments(array $declared, array $incoming): array
    {
        $result = [];
        foreach ($declared as $key) {
            if (!isset($incoming[$key])) {
                throw new \RuntimeException(
                    "Incoming arguments don't have the {$key} parameter"
                );
            }
            $result[] = $incoming[$key];
            unset($incoming[$key]);
        }

        if (count($incoming) > 0) {
            throw new \RuntimeException(
                sprintf(
                    "Found the following arguments '%s' which aren't declared",
                    implode(', ', array_keys($incoming))
                )
            );
        }

        return $result;
    }
}
