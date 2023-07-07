<?php

declare(strict_types=1);

namespace App;

use App\Domain\Exception\DomainException;
use App\Infra\Http\ActionHandler;
use App\Infra\Http\ErrorResponse;
use App\Infra\Http\ResponseFactory;
use Doctrine\DBAL\Types\ConversionException;
use Laminas\HttpHandlerRunner\Emitter\EmitterInterface;
use Nyholm\Psr7Server\ServerRequestCreatorInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Serializer\Exception\MissingConstructorArgumentsException;

final class HttpApp extends AbstractApp implements HttpAppInterface
{
    public function __construct(
        readonly private ServerRequestCreatorInterface $serverRequestCreator,
        readonly private EmitterInterface $emitter,
        readonly private ActionHandler $actionHandler,
        readonly private ResponseFactory $responseFactory,
        readonly private LoggerInterface $logger,
    ) {
    }

    public function createRequestFromGlobals(): ServerRequestInterface
    {
        return $this->serverRequestCreator->fromGlobals();
    }

    public function handle(ServerRequestInterface $request): void
    {
        $this->emitter->emit(
            $this->getResponse($request)
        );
    }

    public function getResponse(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $res = $this->actionHandler->handle($request);
        } catch (ResourceNotFoundException $e) {
            $res = $this->error(
                ErrorResponse::RESOURCE_NOT_FOUND,
                'Resource not found'
            );
        } catch (MethodNotAllowedException $e) {
            $res = $this->error(
                ErrorResponse::METHOD_NOT_ALLOWED,
                'Method not allowed'
            );
        } catch (DomainException $e) {
            $res = $this->error(
                ErrorResponse::BAD_REQUEST,
                $e->getMessage()
            );
        } catch (MissingConstructorArgumentsException $e) {
            $res = $this->error(
                ErrorResponse::BAD_REQUEST,
                'Missed required fields'
            );
        } catch (ConversionException $e) {
            $res =  $this->error(
                ErrorResponse::BAD_REQUEST,
                'Bad fields data format'
            );
        } catch (\Throwable $e) {
            if ($this->isDebug) {
                echo "<pre>";
                throw $e;
            }

            $this->logger->error($e->getMessage());

            $res =  $this->error(
                ErrorResponse::INTERNAL,
                'An error has occurred'
            );
        }

        return $res;
    }

    private function error(int $code, string $message): ResponseInterface
    {
        return $this->responseFactory->create(
            new ErrorResponse($code, $message)
        );
    }
}
