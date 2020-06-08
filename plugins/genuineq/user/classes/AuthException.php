<?php namespace Genuineq\User\Classes;

use Lang;
use Config;
use Exception;
use October\Rain\Exception\ApplicationException;

/**
 * Used when user authentication fails. Implements a softer error message.
 */
class AuthException extends ApplicationException
{
    /**
     * Softens a detailed authentication error with a more vague message when
     * the application is not in debug mode. This is for security reasons.
     * @param string $message Error message.
     * @param int $code Error code.
     * @param Exception $previous Previous exception.
     */
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
