<?php

namespace App\Domain\ValueObject;

use App\Exceptions\ValidatorInvalidArgumentException;
use Illuminate\Support\Facades\Validator;
use JetBrains\PhpStorm\Pure;

trait ValidatableValueObjectTrait
{
    /**
     * 値を検証し、NGなら例外を投げる。OKなら何もしない
     *
     * @param mixed       $value 検証する値
     * @param string|null $name もしこのTraitが使用されている値クラスにstatic $nameプロパティが無ければ、この値を使用する。
     * @return void
     * @throws ValidatorInvalidArgumentException
     */
    final protected static function validate(mixed $value,?string $name = null): void
    {
        $value_name = static::$name ?? $name;

        $data = [$value_name => $value];
        $rule = [$value_name=> static::rule()];
        $message = [$value_name => static::message()];

        $validator = Validator::make($data, $rule, $message);
        if ($validator->fails()) {
            $exception = new ValidatorInvalidArgumentException($value);
            $exception->setErrors($validator->errors());
            throw $exception;
        }
    }

    /**
     * そのValueObjectに適用されるバリデーションルールを定義する
     *
     * @return array
     */
    #[Pure]
    public static function rule(): array
    {
        return [];
    }

    /**
     * そのValueObjectに適用されるバリデーションで表示したいメッセージを定義する
     *
     * @return array
     */
    #[Pure]
    public static function message(): array
    {
        return [];
    }
}
