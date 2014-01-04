<?php

/**
 * Creates SoapClients for KAS Authentication and API
 *
 * @package default
 * @author Elias Kuiter
 */
abstract class KasSoapClient {
  
  /**
   * SoapClient instance
   *
   * @var object
   */
  private $instance;
  
  /**
   * Creates a SoapClient instance
   *
   * @author Elias Kuiter
   */
  function __construct($wsdl) {
    $this->instance = new SoapClient($wsdl);
  }
  
  /**
   * Returns a new WSDL SoapClient
   *
   * @return object
   * @author Elias Kuiter
   */
  public function get() {
    return $this->instance;
  }
}

/**
 * SoapClient for Authentication
 *
 * @package default
 * @author Elias Kuiter
 */
class KasAuthSoapClient extends KasSoapClient {
  
  /**
   * Sets the Authentication WSDL file
   *
   * @param object $kas_configuration 
   * @author Elias Kuiter
   */
  function __construct($kas_configuration) {
    parent::__construct($kas_configuration->wsdl_auth);
  }
}

/**
 * SoapClient for API
 *
 * @package default
 * @author Elias Kuiter
 */
class KasApiSoapClient extends KasSoapClient {
  
  /**
   * Sets the API WSDL file
   *
   * @param object $kas_configuration 
   * @author Elias Kuiter
   */
  function __construct($kas_configuration) {
    parent::__construct($kas_configuration->wsdl_api);
  }
}