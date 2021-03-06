<?php

namespace App\Service\Request;

use function array_key_exists;
use function array_merge;
use function is_array;
use function json_decode;
use JsonException;
use function sprintf;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class RequestService
{
    /**
     * @return mixed|null
     *
     * @throws JsonException
     */
    public static function getField(Request $request, string $fieldName, bool $isRequired = true, bool $isArray = false)
    {
        $requestData = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        if ($isArray) {
            $arrayData = self::arrayFlatten($requestData);

            foreach ($arrayData as $key => $value) {
                if ($fieldName === $key) {
                    return $value;
                }
            }

            if ($isRequired) {
                throw new BadRequestHttpException(sprintf('Missing field %s', $fieldName));
            }

            return null;
        }

        if (array_key_exists($fieldName, $requestData)) {
            return $requestData[$fieldName];
        }

        if ($isRequired) {
            throw new BadRequestHttpException(sprintf('Missing field %s', $fieldName));
        }

        return null;
    }

    public static function arrayFlatten(array $array): array
    {
        $return = [];

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                /** @noinspection SlowArrayOperationsInLoopInspection */
                $return = array_merge($return, self::arrayFlatten($value));
            } else {
                $return[$key] = $value;
            }
        }

        return $return;
    }
}
