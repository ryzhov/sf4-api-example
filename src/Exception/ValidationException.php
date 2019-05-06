<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;

/**
 * Class ValidationException.
 */
class ValidationException extends BadRequestHttpException
{
    /**
     * @var ConstraintViolationInterface[]
     */
    private $errors;

    public function __construct($message, ConstraintViolationListInterface $violations, \Exception $previous = null, $code = 0)
    {
        foreach ($violations as $violation) {
            $this->errors[] = [
                'message' => $violation->getMessage(),
                'property_path' => $violation->getPropertyPath()
            ];
        }

        parent::__construct($message, $previous, $code);
    }

    public function getErrors(): iterable
    {
        return $this->errors;
    }
}