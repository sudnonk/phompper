<?php

namespace App\Domain\Rules;

use Illuminate\Contracts\Validation\Rule;

class EnumValueRule implements Rule
{
    protected $name;
    protected $class;

    public function __construct(string $name, string $class)
    {
        $this->name = $name;
        $this->class = $class;
    }

    public function passes($attribute, $value): bool
    {
        assert(method_exists($this->class, 'isValid'));
        return [$this->class, 'isValid']($value);
    }

    public function message(): array
    {
        return [":inputは:attributeのenumに有りません。"];
    }

}
