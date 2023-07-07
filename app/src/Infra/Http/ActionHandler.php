<?php

declare(strict_types=1);

namespace App\Infra\Http;

use App\Defaults;
use App\Infra\Routing\ActionInterface;
use App\Infra\Routing\DispatcherInterface;
use App\Infra\Routing\DispatchResult;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Serializer\SerializerInterface;

final readonly class ActionHandler
{
    public function __construct(
        private DispatcherInterface $dispatcher,
        private ResponseFactory $responseFactory,
        private SerializerInterface $serializer,
    ) {
    }

    public function handle(ServerRequestInterface $req): ResponseInterface
    {
        $res = $this->dispatcher->dispatch($req);
        $obj = $this->invokeAction($res, $req);
        return $this->responseFactory->create($obj);
    }

    private function invokeAction(
        DispatchResult $res,
        ServerRequestInterface $req
    ): object {
        $action = $res->action;
        if (!is_callable($action)) {
            throw new \RuntimeException(
                sprintf(
                    "The action class %s should have __invoke method",
                    get_class($action)
                )
            );
        }

        $requestClass = $action->getRoute()->actionRequestClass;
        if ($requestClass) {
            $domainRequest = $this->serializer->deserialize(
                $req->getBody(),
                $requestClass,
                Defaults::SERIALIZATION_FORMAT
            );
            return $action($domainRequest, ...$res->arguments);
        }

        return $action(...$res->arguments);
    }
}
