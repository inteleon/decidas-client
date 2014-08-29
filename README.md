# Decidas Client

For making requests to Decidas webservice. You need an account at Decidas.

# Installation (Composer)

Add to your `composer.json`:

```json
"require": {
    "inteleon/decidas-client": "*"
}
```
Then run `composer install`.

## Supported Decidas functions

- PersonSearch (partly)

## PersonSearch

Currently this package only supports to search for one person by social security number (svenskt personnummer).

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
$personnr = '';
$person = $decidas->personSearch($personnr, $confignr);
```

If a person is found `$person` is an array with the keys: `[PersonNr, LastName, FirstName, GivenName, AddressCo, AddressFo, AddressStreet, AddressZip, AddressCity]`

If no person is found `$person` is `false`
