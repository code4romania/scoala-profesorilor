<?php namespace Multiwebinc\Recaptcha\Validators;
use Illuminate\Contracts\Validation\Rule;
use ReCaptcha\ReCaptcha;
use Multiwebinc\Recaptcha\Models\Settings;
use Request;

class RecaptchaValidator implements Rule
{
    protected $error;

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $ip = Request::ip();
        $recaptcha = new ReCaptcha(Settings::get('secret_key'));

        $response = $recaptcha->verify(
            $value,
            $ip
        );

        if (!$response->isSuccess()) {
            $this->error = trans('multiwebinc.recaptcha::lang.errors.validation_error') . ': ' . implode(' / ', $response->getErrorCodes());
        }

        return $response->isSuccess();
    }

    /**
     * Validation callback method.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  array  $params
     * @return bool
     */
    public function validate($attribute, $value, $params)
    {
        return $this->passes($attribute, $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->error;
    }
}