<?php

namespace App\Domain\ValueObject;

use App\Exceptions\ValidatorInvalidArgumentException;
use Illuminate\Support\Facades\Validator;
use phpDocumentor\Reflection\Types\Static_;

abstract class BaseValueObject
{
    protected static $name = "";
    protected $value;

    public function __construct($value)
    {
        self::validate($value);
        $this->value = $value;
    }

    /**
     * 値を検証し、NGなら例外を投げる。OKなら何もしない
     *
     * @param mixed $value 検証する値
     * @return void
     * @throws \InvalidArgumentException
     */
    final protected static function validate($value): void
    {
        $data = [static::$name => $value];
        $rule = [static::$name => static::rule()];
        $message = [static::$name => static::message()];


        $validator = Validator::make($data, $rule, $message);
        if ($validator->fails()) {
            $exception = new ValidatorInvalidArgumentException($value);
            $exception->setErrors($validator->errors());
            throw $exception;
        }
    }

    public function getValue()
    {
        return $this->value;
    }

    /**
     * そのValueObjectに適用されるバリデーションルールを定義する
     *
     * @return array
     */
    public static function rule(): array
    {
        return [];
    }

    /**
     * そのValueObjectに適用されるバリデーションで表示したいメッセージを定義する
     *
     * @return array
     */
    public static function message(): array
    {
        return [];
    }
}
