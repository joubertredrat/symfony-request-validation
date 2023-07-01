<?php

declare(strict_types=1);

namespace App\Request;

use Fig\Http\Message\StatusCodeInterface;
use Jawira\CaseConverter\Convert;
use ReflectionClass;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractJsonRequest
{
    private const FORMAT_JSON = 'json';

    public function __construct(
        protected ValidatorInterface $validator,
        protected RequestStack $requestStack,
    ) {
        $this->populate();
        $this->validate();
    }

    public function getRequest(): Request
    {
        return $this->requestStack->getCurrentRequest();
    }

    protected function populate(): void
    {
        $request = $this->getRequest();
        if (!self::isValidFormat($request)) {
            $response = new JsonResponse(
                ['error' => 'expected application/json on header Content-Type request'],
                StatusCodeInterface::STATUS_BAD_REQUEST,
            );
            $response->send();
            exit;
        }

        $reflection = new ReflectionClass($this);

        foreach ($request->toArray() as $property => $value) {
            $attribute = self::camelCase($property);
            if (property_exists($this, $attribute)) {
                $reflectionProperty = $reflection->getProperty($attribute);
                $reflectionProperty->setValue($this, $value);
            }
        }
    }

    protected function validate(): void
    {
        $errors = $this->validator->validate($this);
        $messages = [];

        /** @var \Symfony\Component\Validator\ConstraintViolation */
        foreach ($errors as $message) {
            $messages[] = [
                'property' => self::snakeCase($message->getPropertyPath()),
                'value' => $message->getInvalidValue(),
                'message' => $message->getMessage(),
            ];
        }

        if (count($messages) > 0) {
            $response = new JsonResponse(['errors' => $messages], StatusCodeInterface::STATUS_BAD_REQUEST);
            $response->send();
            exit;
        }
    }

    private static function isValidFormat(Request $request): bool
    {
        return \in_array($request->getContentTypeFormat(), self::getFormatsAvailable());
    }

    private static function getFormatsAvailable(): array
    {
        return [self::FORMAT_JSON];
    }

    private static function camelCase(string $field): string
    {
        return (new Convert($field))->toCamel();
    }

    private static function snakeCase(string $field): string
    {
        return (new Convert($field))->toSnake();
    }
}
