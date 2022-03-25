<?php

namespace App\Exceptions;

use Illuminate\Support\MessageBag;
use Throwable;

class ValidatorInvalidArgumentException extends \InvalidArgumentException
{
    /** @var mixed $value この例外を引き起こしたInvalidな値 */
    protected $value;
    /** @var MessageBag $errors */
    protected $errors;

    public function __construct($value, Throwable $previous = null, $code = 400)
    {
        $message = "ValidatorInvalidArgumentException occurred.";
        parent::__construct($message, $code, $previous);
        $this->value = $value;
        $this->errors = new MessageBag();
    }

    /**
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
    public function getValue(){
        return $this->value;
    }
}
