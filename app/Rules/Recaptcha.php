<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class Recaptcha implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $this->verify((string) $value)) {
            $fail($this->message());
        }
    }

    public function verify(string $value): bool
    {
        $response = Http
            ::withOptions(['verify' => false])
            ->asForm()
            ->post(config('services.google_recaptcha.url'), [
            'secret' => config('services.google_recaptcha.secret_key'),
            'response' => $value,
        ])->json();

        if(  $response['success'] && $response['score'] > 0.5) {
            return true;
        }

        return false;
    }

    public function message(): string
    {
        return __('validation.recaptcha');
    }
}
