<?php
/**
 * @file Luminance/Security/Csrf.php
 * @namespace Luminance\Security
 * @version 1.0.0
 * @copyright 2017-2018
 * @license MIT
 */

namespace Luminance\Security;

/**
 * Csrf is apart of security, and provides Csrf Tokens, Validation, and Expiration
 *
 * @author Michael <michaeldoestech@gmail.com>
 */
class Csrf
{
    /**
     * @var string
     */
    protected $token = "";

    /**
     * @var string
     */
    protected $session_key = "CSRF_TOKEN";

    /**
     * @var string
     */
    public static $CSRF_FORM_INPUT_STRING = "<input type='hidden' name='csrf_token' value='%s' />";

    /**
     * This will generate a secure random token using openssl_random_psuedo_bytes
     */
    protected function generate()
    {
        $this->token = base64_encode(openssl_random_pseudo_bytes(32));
    }

    /**
     * This will set the token, and then set it into the session
     */
    protected function setToken()
    {
        $this->generate();
        $this->setSession();
    }

    /**
     * Sets the session variable into the $_SESSION object
     */
    protected function setSession()
    {
        $_SESSION[$this->session_key] = $this->token;
    }

    /**
     * Wrapper for local setToken function
     */
    public function generateToken()
    {
        $this->setToken();
    }

    /**
     * Get the current token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Get the token from session variable, and assign it locally
     */
    protected function getTokenFromSession()
    {
        $this->token = $_SESSION[$this->session_key];
    }

    /**
     * Generate the form string, and sprintf the templated string
     *
     * @return string
     */
    public function generateForm()
    {
        return sprintf(self::$CSRF_FORM_INPUT_STRING, $this->getToken());
    }

    /**
     * Validates if the session token matches the one we expected from the client, rooms a boolean status
     *
     * @return bool
     */
    public function tokenValid()
    {
        if(isset($_SESSION[$this->session_key]) && $_POST['CSRF_TOKEN'] == $_SESSION[$this->session_key])
        {
            return true;
        }
        return false;
    }
}