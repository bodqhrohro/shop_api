<?php

namespace AppBundle\Validation;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\HttpKernel\Exception\HttpException;

class DefaultValidator
{
    protected const ORDER_ORIENTATION = ['ASC', 'DESC'];
    protected const DATETIME_FORMAT = '!Y-m-d';

    public static function validateInput(Object $input)
    {
        $violations = [];
        $validator = Validation::createValidator();

        if (isset($input->count)) {
            $violations[] = $validator->validate($input->count, [
                new Type(['type' => 'integer']),
                new GreaterThan(0),
            ]);
        }

        if (isset($input->price)) {
            if (isset($input->price->min)) {
                $violations[] = $validator->validate($input->price->min, [
                    new Type(['type' => 'numeric']),
                    new GreaterThan(0),
                ]);
            }
            if (isset($input->price->max)) {
                $violations[] = $validator->validate($input->price->max, [
                    new Type(['type' => 'numeric']),
                    new GreaterThanOrEqual(isset($input->price->min) ? $input->price->min : 0),
                ]);
            }
        }

        if (isset($input->created_at)) {
            if (isset($input->created_at->min)) {
                $input->created_at->min = \DateTime::createFromFormat(
                    self::DATETIME_FORMAT,
                    $input->created_at->min
                );
                $violations[] = $validator->validate($input->created_at->min, [
                    new Date(),
                ]);
            }
            if (isset($input->created_at->max)) {
                $input->created_at->max = \DateTime::createFromFormat(
                    self::DATETIME_FORMAT,
                    $input->created_at->max
                );
                $constraints = [
                    new Date(),
                ];
                if (isset($input->created_at->min)) {
                    $constraints[] = new GreaterThanOrEqual($input->created_at->min);
                }
                $violations[] = $validator->validate($input->created_at->max, $constraints);
            }
            if (isset($input->created_at->sort)) {
                $violations[] = $validator->validate($input->created_at->sort, [
                    new Choice(['choices' => self::ORDER_ORIENTATION]),
                ]);
            }
        }

        if (isset($input->name)) {
            if (isset($input->name->sort)) {
                $violations[] = $validator->validate($input->name->sort, [
                    new Choice(['choices' => self::ORDER_ORIENTATION]),
                ]);
            }
        }

        foreach ($violations as $field_violations) {
            if (\count($field_violations)) {
                foreach ($field_violations as $violation) {
                    $invalidValue = $violation->getInvalidValue();
                    if ($invalidValue instanceof \DateTime) {
                        $invalidValue = $invalidValue->format(self::DATETIME_FORMAT);
                    }
                    throw new HttpException(400, $invalidValue . ': ' . $violation->getMessage());
                }
            }
        }

        return $input;
    }
}
