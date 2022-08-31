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
     * might be plain
     */
    public $_authType = "plain";

	/**
	 * Automatic Delay for Api Calls
	 *
	 * Manages whether KasApi should use sleep to automagically manage kasFloodDelay
	 */
	public $_autoDelayApiCalls;

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
     * @param bool $autoDelayApiCalls
     */
    function __construct($username, $authData, $authType, $autoDelayApiCalls = false) {
        $this->_username = $username;
        $this->_authData = $authData;
        $this->_authType = $authType;
        $this->_autoDelayApiCalls = $autoDelayApiCalls;
    }
}
