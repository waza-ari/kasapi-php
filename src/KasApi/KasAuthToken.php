<?php

namespace KasApi;

/**
 * Fetches an Authentication token.
 * Makes use of a KAS configuration and of SOAP objects
 *
 * @package default
 * @author Elias Kuiter
 */
class KasAuthToken {
  
  /**
   * KasConfiguration object
   *
   * @var KasConfiguration $kas_configuration
   */
  private $kas_configuration;
  
  /**
   * Sets the KasConfiguration
   *
   * @param object $kas_configuration 
   * @author Elias Kuiter
   */
  function __construct($kas_configuration) {
    $this->kas_configuration = $kas_configuration;
  }
  
  /**
   * Saves the authentication token to the session
   *
   * @param string $token 
   * @return string
   * @author Elias Kuiter
   */
  private function saveAuth($token) {
    return $_SESSION['auth'] = $token;
  }
  
  /**
   * Loads the authentication token from the session
   *
   * @return string
   * @author Elias Kuiter
   */
  private function loadAuth() {
    return $_SESSION['auth'];
  }
  
  /**
   * Saves the auth expiration UNIX time to the session
   *
   * @param string $token 
   * @return string
   * @author Elias Kuiter
   */
  private function saveAuthExpires($token) {
    return $_SESSION['auth_expires'] = $token;
  }
  
  /**
   * Loads the auth expiration UNIX time from the session
   * 
   * @return string
   * @author Elias Kuiter
   */
  private function loadAuthExpires() {
    return $_SESSION['auth_expires'];
  }
  
  /**
   * Fetches an authentication token via a SoapClient
   *
   * @return string
   * @author Elias Kuiter
   */
  private function fetch() {
    $client = (new KasSoapClient($this->kas_configuration->wsdl_auth))->getInstance();
    return $client->KasAuth($this->kas_configuration->to_json());
  }
  
  /**
   * Whether the authentication token is still valid.
   * 10 seconds tolerance
   *
   * @return boolean
   * @author Elias Kuiter
   */
  private function authValid() {
    return $this->loadAuthExpires() > time() - 10;
  }
  
  /**
   * Gets the saved authentication token if still valid, fetches one if necessary.
   * Also takes care of the expiration time
   *
   * @return string
   * @author Elias Kuiter
   */
  public function get() {
    if ($this->loadAuth() && $this->authValid())
      return $this->loadAuth();
    $this->saveAuthExpires(time() + $this->kas_configuration->session_lifetime);
    return $this->saveAuth($this->fetch());
  }
}