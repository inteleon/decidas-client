<?php
namespace Inteleon;

use Inteleon\Exception\InteleonSoapClientException;
use Inteleon\Exception\DecidasClientException;
use SoapClient;
use SoapFault;

class DecidasClient
{
	/** @var SoapClient */
	protected $soap_client;

	/** @var string Decidas Username */
	protected $username;
	
	/** @var string Decidas password */
	protected $password;

	/** @var int The number of milliseconds to wait while trying to connect. */
	protected $connect_timeout;
	
	/** @var int The maximum number of milliseconds to allow execution */
	protected $timeout;
	
	/** @var int Number of connect attempts to be made if connection error occurs */
	protected $connect_attempts;	
	
	/** @var boolean Verify Decidas certificate */
	protected $verify_certificate;

	/** @var boolean Cache the WSDL */
	protected $cache_wsdl;
	
	/**
	 * Constructor
	 *
	 * @param string $username Decidas username
	 * @param string $password Decidas Password
	 * @param integer $connect_timeout Connect timeout in milliseconds
	 * @param integer $timeout Timeout in milliseconds
	 * @param int $connect_attempts Number of connect attempts
	 * @param boolean $verify_certificate Verify Decidas certificate
	 * @param boolean $cache_wsdl Cache the WSDL
	 */
	public function __construct($username, $password, $connect_timeout = 30000, $timeout = 30000, $connect_attempts = 1, $verify_certificate = true, $cache_wsdl = true)
	{
		$this->username = $username;
		$this->password = $password;
		$this->connect_timeout = $connect_timeout;
		$this->timeout = $timeout;
		$this->connect_attempts = $connect_attempts;
		$this->verify_certificate = $verify_certificate;
		$this->cache_wsdl = $cache_wsdl ? WSDL_CACHE_BOTH : WSDL_CACHE_NONE;
	}

	/**
	 * Make a request to PersonSearch
	 *
	 * @param string $PersonNr Swedish id-number
	 * @param string $ConfigID Defines the product the questions uses.
	 * @return false|array False if no hit or array with person information
	 */
	public function personSearch($PersonNr, $ConfigID)
	{
		try {	
			$soap_client = $this->getSoapClient();		
			$request = array(
				'searchQuestion' => array(
					'ConfigID' => $ConfigID,
					'PersonNr' => $PersonNr
				)
			);			
			$response = $soap_client->__soapCall('PersonSearch', array($request), array('location' => 'https://securews.decidas.com/DecidasService.asmx'));

		} catch (SoapFault $sf){

			$error_string = '[' . $sf->faultcode . '] ' . $sf->faultstring;
			
			if (isset($sf->faultdetail) && $sf->faultdetail) {
				$error_string .= ' (' . $sf->faultdetail . ')';
			}

			throw new DecidasClientException($error_string);	

		} catch (InteleonSoapClientException $e) {

			throw new DecidasClientException('Connection error (' . $e->getMessage() . ')');			
		}		

		if ($response->PersonSearchResult->PersonsFound == 0) {
			return false;
		}
		
		if ($response->PersonSearchResult->PersonsFound > 1) {
			throw new DecidasClientException('Multiple persons found');
		}
		
		$result = array(
			'PersonNr' => $response->PersonSearchResult->Persons->Person->PersonNr,
			'LastName' => $response->PersonSearchResult->Persons->Person->LastName,
			'FirstName' => $response->PersonSearchResult->Persons->Person->FirstName,
			'GivenName' => $response->PersonSearchResult->Persons->Person->GivenName,
			'AddressCo' => $response->PersonSearchResult->Persons->Person->AddressCo,
			'AddressFo' => $response->PersonSearchResult->Persons->Person->AddressFo,
			'AddressStreet' => $response->PersonSearchResult->Persons->Person->AddressStreet,
			'AddressZip' => $response->PersonSearchResult->Persons->Person->AddressZip,
			'AddressCity' => $response->PersonSearchResult->Persons->Person->AddressCity
		);

		return $result;
	}
	
	/**
	 * Set your own Soap Client.
	 *
	 * @param SoapClient $soap_client
	 */
	public function setSoapClient(SoapClient $soap_client)
	{
		$this->soap_client = $soap_client;
	}

	/**
	 * Get the Soap Client
	 *
	 * @return SoapClient
	 */
	protected function getSoapClient()
	{
		//Already instantiated
		if (isset($this->soap_client)) {
			return $this->soap_client;		
		}
		
		try {
			$soap_client = new InteleonSoapClient('https://securews.decidas.com/DecidasService.asmx?WSDL', array(
				'authentication' => SOAP_AUTHENTICATION_BASIC,
				'login' => $this->username,
				'password' => $this->password,
				'exceptions' => true,
				'trace' => false,
				'cache_wsdl' => $this->cache_wsdl,
			));
			$soap_client->setTimeout($this->timeout);
			$soap_client->setConnectTimeout($this->connect_timeout);
			$soap_client->setConnectAttempts($this->connect_attempts);
			$soap_client->setVerifyCertificate($this->verify_certificate);
			
		} catch (SoapFault $sf){

			$error_string = '[' . $sf->faultcode . '] ' . $sf->faultstring;
			
			if (isset($sf->faultdetail) && $sf->faultdetail) {
				$error_string .= ' (' . $sf->faultdetail . ')';
			}

			throw new DecidasClientException($error_string);	

		} catch (InteleonSoapClientException $e) {

			throw new Exception('Connection error (' . $e->getMessage() . ')');			
		}
		
		return $this->soap_client = $soap_client;			
	}		
}