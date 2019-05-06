<?php

namespace App\ArgumentResolver;

use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use App\Exception\ValidationException;
use App\Mapper\RequestMapperInterface;
use App\DTO\RequestDTOInterface;
use App\DTO\ValidatedDTOInterface;

/**
 * Class RequestDTOResolver.
 */
class RequestDTOResolver implements ArgumentValueResolverInterface
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var RequestMapperInterface
     */
    private $mapper;

    public function __construct(ValidatorInterface $validator, RequestMapperInterface $mapper)
    {
        $this->validator = $validator;
        $this->mapper = $mapper;
    }

    /**
     * @param Request          $request
     * @param ArgumentMetadata $argument
     *
     * @return bool
     *
     * @throws \ReflectionException
     */
    public function supports(Request $request, ArgumentMetadata $argument)
    {
        if (null === $argument->getType() || !class_exists($argument->getType())) {
            return false;
        }

        $reflection = new \ReflectionClass($argument->getType());

        if ($reflection->implementsInterface(RequestDTOInterface::class)) {
            return true;
        }

        return false;
    }

    /**
     * @param Request $request
     * @param ArgumentMetadata $argument
     *
     * @return \Generator
     */
    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        $class = $argument->getType();

        try {
            $dto = $this->mapper->map($request, $class);
        } catch (ExceptionInterface $ex) {
            throw new BadRequestHttpException(
                sprintf('Mapping into "%s" class failed => %s', $class, $ex->getMessage()),
                $ex
            );
        }

        if ($dto instanceof ValidatedDTOInterface) {
            $errors = $this->validator->validate($dto);

            if (count($errors) > 0) {
                throw new ValidationException(
                    sprintf('object of class: "%s" validation fail', get_class($dto)),
                    $errors
                );
            }
        }

        yield $dto;
    }
}
