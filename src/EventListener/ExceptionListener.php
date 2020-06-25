<?php

namespace App\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

/**
 * Class BaseExceptionListener
 * @package App\EventListener\Exception
 */
class ExceptionListener
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * AbstractExceptionListener constructor.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param ExceptionEvent $event
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $errorCode = JsonResponse::HTTP_INTERNAL_SERVER_ERROR;
        $error = 'Unexpected Error';

        switch (get_class($exception)) {
            case 'Doctrine\DBAL\Exception\UniqueConstraintViolationException':
                $errorCode = JsonResponse::HTTP_BAD_REQUEST;
                $error = 'The element already exists';
                break;
            case 'Symfony\Component\HttpKernel\Exception\BadRequestHttpException':
                $errorCode = JsonResponse::HTTP_BAD_REQUEST;
                $error = $exception->getMessage();
                break;
            case 'Symfony\Component\HttpKernel\Exception\NotFoundHttpException':
                $errorCode = JsonResponse::HTTP_NOT_FOUND;
                $error = $exception->getMessage();
                break;
        }

        $event->setResponse(new JsonResponse(
            [
                'code' => $errorCode,
                'error' => $error,
            ],
            $errorCode
        ));
    }

    /**
     * @param $exception
     * @param mixed $message
     */
    protected function logException(string $message, \Exception $exception): void
    {
        $this->logger->error($message, [
            'class' => \get_class($exception),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
        ]);
    }
}