<?php

namespace App\Adapter;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BaseConverter
{
    /** @var ValidatorInterface */
    private $validator;

    /**
     * BaseConverter constructor.
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param string $parameterName
     * @param $parameter
     * @param $constraint
     */
    protected function validateParam(string $parameterName, $parameter, $constraint): void
    {
        $errors = $this->validator->validate(
            $parameter,
            $constraint
        );

        if (count($errors)) {
            throw new BadRequestHttpException($parameterName. ': ' . $errors[0]->getMessage());
        }
    }

    /**
     * @param array $data
     * @param array $parameters
     */
    protected function validateMandatory(array $data, array $parameters): void
    {
        foreach($parameters as $parameter) {
            if (!isset($data[$parameter])) {
                throw new BadRequestHttpException($parameter . ' is mandatory');
            }
        }
    }
}
