<?php

namespace Tests\Unit\Rules;

use App\Rules\Recaptcha;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class RecaptchaTest extends TestCase
{
    protected Recaptcha $rule;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rule = new Recaptcha();
    }

    public function test_validation_passes_when_recaptcha_verify_returns_success(): void
    {
        Http::fake([
            config('services.google_recaptcha.url') => Http::response(['success' => true], 200),
        ]);

        $validator = Validator::make(
            ['g-recaptcha-response' => 'valid-token'],
            ['g-recaptcha-response' => [$this->rule]]
        );

        $this->assertFalse($validator->fails());
    }

    public function test_validation_fails_when_recaptcha_verify_returns_failure(): void
    {
        Http::fake([
            config('services.google_recaptcha.url') => Http::response(['success' => false], 200),
        ]);

        $validator = Validator::make(
            ['g-recaptcha-response' => 'invalid-token'],
            ['g-recaptcha-response' => [$this->rule]]
        );

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('g-recaptcha-response', $validator->errors()->messages());
    }

    public function test_verify_returns_true_when_google_responds_success(): void
    {
        Http::fake([
            config('services.google_recaptcha.url') => Http::response(['success' => true], 200),
        ]);

        $result = $this->rule->verify('some-token');

        $this->assertTrue($result);
    }

    public function test_verify_returns_false_when_google_responds_failure(): void
    {
        Http::fake([
            config('services.google_recaptcha.url') => Http::response(['success' => false], 200),
        ]);

        $result = $this->rule->verify('bad-token');

        $this->assertFalse($result);
    }

    public function test_message_returns_translation_key(): void
    {
        $message = $this->rule->message();

        $this->assertIsString($message);
        // May be the key 'validation.recaptcha' or the translated string
        $this->assertNotEmpty($message);
    }
}
