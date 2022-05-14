<?php

namespace App\Exceptions;

use Illuminate\Support\MessageBag;
use Throwable;

class ValidatorInvalidArgumentException extends \InvalidArgumentException
{
    /** @var mixed $value この例外を引き起こしたInvalidな値 */
    protected mixed $value;
    /** @var MessageBag $errors */
    protected MessageBag $errors;

    public function __construct($value = null, Throwable $previous = null, $code = 400)
    {
        $message = "ValidatorInvalidArgumentException occurred.";
        parent::__construct($message, $code, $previous);
        $this->value = $value;
        $this->errors = new MessageBag();
    }

    /**
     * エラーをstringから追加する。
     *
     * @param string $name     そのエラーの名前。例：年齢を入れるinput fieldでエラーが有った場合は、「年齢」
     * @param string $errorMsg そのエラーのメッセージ。例：年齢を入れるinput fieldでエラーが合ったは、「年齢が間違っています」
     * @return void
     */
    public function setError(string $name, string $errorMsg): void
    {
        $this->errors = $this->errors->add($name, $errorMsg);
    }

    /**
     * エラーをMessageBagから追加する。例えばValidator::make()から出てくるバリデーションエラーはMessageBagに格納された形で出てくるので、それをこの例外に追加したいときなど。
     *
     * @param MessageBag $errors
     * @return void
     */
    public function setErrors(MessageBag $errors): void
    {
        $this->errors = $this->errors->merge($errors);
    }

    /**
     * エラーメッセージの入ったMessageBagを返す
     *
     * @return MessageBag
     */
    public function getErrors(): MessageBag
    {
        return $this->errors;
    }

    /**
     * このExceptionを引き起こしたInvalidな値を返す
     *
     * @return mixed
     */
    public function getValue(): mixed
    {
        return $this->value;
    }
}
