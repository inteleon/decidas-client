# Decidas client

For making requests to Decidas webservice

## Example

```php
$decidas = new Inteleon\DecidasClient($username, $password);

if ($person = $decidas->personSearch($PersonNr, $ConfigNr) {
    print_r($person);
}
```