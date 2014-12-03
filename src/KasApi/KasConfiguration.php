<?php

namespace KasApi;

/**
 * Contains configuration values for the KAS API
 *
 * @package KasApi
 */
class KasConfiguration {

    /**
     * KAS username
     *
     * @var string
     */
    public $_username;

    /**
     * KAS Auth String
     *
     * @var string
     */
    public $_authData;

    /**
     * Auth Type
     *
     * might be sha1
     */
    public $_authType = "sha1";

    /**
     * WSDL file for KAS API
     *
     * @var string
     */
    public $wsdl_api = 'https://kasapi.kasserver.com/soap/wsdl/KasApi.wsdl';

    /**
     * Creates a new Configuration object with the given parameters
     *
     * @param string $username
     * @param string $authData
     * @param string $authType
     */
    function __construct($username, $authData, $authType) {
        $this->_username = $username;
        $this->_authData = $authData;
        $this->_authType = $authType;
    }
}