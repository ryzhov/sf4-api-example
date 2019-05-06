<?php

namespace App\Mapper;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception\ExceptionInterface;


/**
 * Interface RequestMapperInterface
 * @package ApiBundle\Mapper
 */
interface RequestMapperInterface
{
    /**
     * Map all the properties from $request to object of class $class. Return a new object.
     *
     * @param Request $request
     * @param string $class
     * @throws ExceptionInterface
     *
     * @return mixed
     */
    public function map(Request $request, string $class);
}