<?php

use Inteleon\Decidas\Client;

class ClientTest extends PHPUnit_Framework_TestCase
{
    protected function createSoapClientMock($response)
    {
        $soap_client_mock = $this->getMockBuilder('SoapClient')
                     ->setMethods(array('__soapCall'))
                     ->disableOriginalConstructor()
                     ->getMock();

        $soap_client_mock->expects($this->any())
             ->method('__soapCall')
             ->will($this->returnValue($response));

        return $soap_client_mock;
    }

    public function testPersonSearch()
    {
        //Dummy response
        $dummy_decidas_response = unserialize('O:8:"stdClass":1:{s:18:"PersonSearchResult";O:8:"stdClass":3:{s:9:"Reference";s:0:"";s:12:"PersonsFound";i:1;s:7:"Persons";O:8:"stdClass":1:{s:6:"Person";O:8:"stdClass":15:{s:15:"ForbiddenPerson";b:0;s:16:"PersonIdentified";b:1;s:8:"PersonNr";s:12:"191111111111";s:8:"LastName";s:14:"Testperson Ett";s:9:"FirstName";s:12:"Decidas Info";s:9:"GivenName";s:7:"Decidas";s:9:"AddressCo";s:0:"";s:9:"AddressFo";s:0:"";s:13:"AddressStreet";s:12:"Testvägen 1";s:10:"AddressZip";s:5:"11111";s:11:"AddressCity";s:8:"Teststad";s:11:"CreditLimit";s:2:"-1";s:11:"CreditScore";s:2:"-1";s:13:"CreditGranted";b:0;s:12:"StatusString";s:5:"Aktiv";}}}}');
        $soap_client_mock = $this->createSoapClientMock($dummy_decidas_response);

        $decidas = new Client(null, null);
        $decidas->setSoapClient($soap_client_mock);
        $result = $decidas->personSearch(null, null);

        $this->assertArrayHasKey('PersonNr', $result);
        $this->assertArrayHasKey('LastName', $result);
        $this->assertArrayHasKey('FirstName', $result);
        $this->assertArrayHasKey('GivenName', $result);
        $this->assertArrayHasKey('AddressCo', $result);
        $this->assertArrayHasKey('AddressFo', $result);
        $this->assertArrayHasKey('AddressStreet', $result);
        $this->assertArrayHasKey('AddressZip', $result);
        $this->assertArrayHasKey('AddressCity', $result);
        $this->assertEquals($result['PersonNr'], '191111111111');
        $this->assertEquals($result['LastName'], 'Testperson Ett');
        $this->assertEquals($result['FirstName'], 'Decidas Info');
        $this->assertEquals($result['GivenName'], 'Decidas');
        $this->assertEquals($result['AddressCo'], '');
        $this->assertEquals($result['AddressFo'], '');
        $this->assertEquals($result['AddressStreet'], 'Testvägen 1');
        $this->assertEquals($result['AddressZip'], '11111');
        $this->assertEquals($result['AddressCity'], 'Teststad');
    }

    public function testPersonSearchNotFound()
    {
        //Dummy response
        $dummy_decidas_response = unserialize('O:8:"stdClass":1:{s:18:"PersonSearchResult";O:8:"stdClass":3:{s:9:"Reference";s:0:"";s:12:"PersonsFound";i:0;s:7:"Persons";O:8:"stdClass":1:{s:6:"Person";O:8:"stdClass":15:{s:15:"ForbiddenPerson";b:0;s:16:"PersonIdentified";b:0;s:8:"PersonNr";s:0:"";s:8:"LastName";s:0:"";s:9:"FirstName";s:0:"";s:9:"GivenName";s:0:"";s:9:"AddressCo";s:0:"";s:9:"AddressFo";s:0:"";s:13:"AddressStreet";s:0:"";s:10:"AddressZip";s:0:"";s:11:"AddressCity";s:0:"";s:11:"CreditLimit";s:2:"-1";s:11:"CreditScore";s:2:"-1";s:13:"CreditGranted";b:0;s:12:"StatusString";s:0:"";}}}}');
        $soap_client_mock = $this->createSoapClientMock($dummy_decidas_response);

        $decidas = new Client(null, null);
        $decidas->setSoapClient($soap_client_mock);
        $result = $decidas->personSearch(null, null);

        $this->assertFalse($result);
    }
}
