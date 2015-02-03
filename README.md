# Decidas Client

For making requests to Decidas webservice. This package is currently build only for looking up person data by a personnummer. You need an account for Decidas and a product configured for this (ConfigID).

## Person data by personnummer

Get person data for a swedish personnummer.

```php
//Create client
$username = ''; //Decidas username
$password = ''; //Decidas password
$connect_timeout = 5000; //Connect timeout in ms
$timeout = 5000; //Timeout in ms
$connect_attempts = 1; //Reconnect attempts if connection is failed
$verify_certificate = true; //Verify the SSL certificate
$cache_wsdl = true; //Cache the WSDL file
$decidas = new Inteleon\Decidas\Client($username, $password, $connect_timeout, $timeout, $connect_attempts, $verify_certificate, $cache_wsdl);

//Person search
$confignr = ''; //Decidas Config number/id
$personnr = ''; //Personnummer
$person = $decidas->personSearch($personnr, $confignr);
```

If a person is found `$person` is an array with the keys: `[PersonNr, LastName, FirstName, GivenName, AddressCo, AddressFo, AddressStreet, AddressZip, AddressCity]`

If no person is found `$person` is `false`
