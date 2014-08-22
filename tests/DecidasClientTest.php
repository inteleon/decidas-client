<?php

use Inteleon\DecidasClient;

class DecidasClientTest extends PHPUnit_Framework_TestCase
{
    public function testPersonSearchMock()
    {
        $decidas = new DecidasClient('', '');

        $dummy_decidas_response = unserialize('O:8:"stdClass":1:{s:18:"PersonSearchResult";O:8:"stdClass":3:{s:9:"Reference";s:0:"";s:12:"PersonsFound";i:1;s:7:"Persons";O:8:"stdClass":1:{s:6:"Person";O:8:"stdClass":15:{s:15:"ForbiddenPerson";b:0;s:16:"PersonIdentified";b:1;s:8:"PersonNr";s:12:"191111111111";s:8:"LastName";s:14:"Testperson Ett";s:9:"FirstName";s:12:"Decidas Info";s:9:"GivenName";s:7:"Decidas";s:9:"AddressCo";s:0:"";s:9:"AddressFo";s:0:"";s:13:"AddressStreet";s:12:"Testvägen 1";s:10:"AddressZip";s:5:"11111";s:11:"AddressCity";s:8:"Teststad";s:11:"CreditLimit";s:2:"-1";s:11:"CreditScore";s:2:"-1";s:13:"CreditGranted";b:0;s:12:"StatusString";s:5:"Aktiv";}}}}');

        $mock = $this->getMockBuilder('SoapClient')
                     ->setMethods(array('__soapCall'))
                     ->disableOriginalConstructor()
                     ->getMock();

        $mock->expects($this->any())
             ->method('__soapCall')
             ->will($this->returnValue($dummy_decidas_response));

        $decidas->setSoapClient($mock);
        $response = $decidas->personSearch('', '');
        
        $this->assertArrayHasKey('PersonNr', $response);
        $this->assertArrayHasKey('LastName', $response);
        $this->assertArrayHasKey('FirstName', $response);
        $this->assertArrayHasKey('GivenName', $response);
        $this->assertArrayHasKey('AddressCo', $response);
        $this->assertArrayHasKey('AddressFo', $response);
        $this->assertArrayHasKey('AddressStreet', $response);
        $this->assertArrayHasKey('AddressZip', $response);
        $this->assertArrayHasKey('AddressCity', $response);

        $this->assertEquals($response['PersonNr'], '191111111111');
        $this->assertEquals($response['LastName'], 'Testperson Ett');
        $this->assertEquals($response['FirstName'], 'Decidas Info');
        $this->assertEquals($response['GivenName'], 'Decidas');
        $this->assertEquals($response['AddressCo'], '');
        $this->assertEquals($response['AddressFo'], '');
        $this->assertEquals($response['AddressStreet'], 'Testvägen 1');
        $this->assertEquals($response['AddressZip'], '11111');
        $this->assertEquals($response['AddressCity'], 'Teststad');     
    }
}