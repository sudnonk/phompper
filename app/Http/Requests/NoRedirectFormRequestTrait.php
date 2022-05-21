<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\MessageBag;

trait NoRedirectFormRequestTrait
{
    protected bool $isValidationFailed = false;
    protected MessageBag|null $validationMsg = null;

    /**
     * バリデーションに失敗しても何もしない
     *
     * @param Validator $validator
     * @return void
     */
    protected function failedValidation(Validator $validator): void
    {
        $this->isValidationFailed = $validator->fails();
        $this->validationMsg = $validator->errors();
    }

    public function isValidationFailed(): bool
    {
        return $this->isValidationFailed;
    }

    public function getValidationErrorMsg(): MessageBag|null
    {
        return $this->validationMsg;
    }
}
