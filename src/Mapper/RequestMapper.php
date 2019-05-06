<?php

namespace App\Mapper;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;


/**
 * Class RequestMapper.
 */
class RequestMapper implements RequestMapperInterface
{
    /**
     * @var ObjectNormalizer
     */
    private $normalizer;

    /**
     * RequestMapper constructor.
     *
     * @param ObjectNormalizer $normalizer
     */
    public function __construct(ObjectNormalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    /**
     * {@inheritdoc}
     *
     * @throws ExceptionInterface
     */
    public function map(Request $request, string $class)
    {
        $data = $this->extractData($request);

        $dto = $this->normalizer->denormalize($data, $class);

        return $dto;
    }

    /**
     * Return an array of data from request.
     *
     * @param Request $request
     * @return array
     */
    private function extractData(Request $request)
    {
        if ('GET' === $request->getMethod()) {
            $data = $request->query->all();
        } else {
            $data = $request->request->all();
        }

        return $data;
    }
}