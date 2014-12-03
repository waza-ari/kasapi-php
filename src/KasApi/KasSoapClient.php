<?php

namespace KasApi;

/**
 * Creates SoapClients for KAS Authentication and API
 *
 * @package KasApi
 */
class KasSoapClient {
  
  /**
   * SoapClient instance
   *
   * @var object
   */
  private $instance;
  
  /**
   * Creates a SoapClient instance
   */
  function __construct($wsdl) {
    $this->instance = new \SoapClient($wsdl);
  }
  
  /**
   * Returns a new WSDL SoapClient
   *
   * @return object
   */
  public function getInstance() {
    return $this->instance;
  }
}