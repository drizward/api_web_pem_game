# API Web Pemrograman Game
Change the ApiToken.php to suit your token and identifier configuration

One line use
```php
$api->send($destination_id, $message)
```

Example
```php
$api = new Api\FacebookApi();
$api->send('drizward', 'Registrasi berhasil, harap masukkan kode AAAAAA');
```