<?php

namespace App\Rules;

use App\Services\ViaCEP;
use Illuminate\Contracts\Validation\Rule;

class ValidaCep implements Rule
{

    public ViaCEP $viaCep;

    public function __construct(
        ViaCEP $viaCep
    ) {
        $this->viaCep = $viaCep;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $cep = str_replace('-', '', $value);

        return !!$this->viaCep->buscar($cep);
        // true passou false não passou na validacao
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Cep inválido';
    }
}
