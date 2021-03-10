### [capturely.app](https://capturely.app) web/html to pdf/png/jpeg converter

### Install

```composer
composer require capturely/capturely-laravel
```

#### config/services.php

```php
'capturely' => [
    'token' => env('CAPTURELY_TOKEN', 'INSERT_TOKEN'),
],
```

### Usage PDF

```php
<?php

use Capturely\Capturely;

// Returns Response with URL & File Size
$capture = Capturely::url('https://google.com')->screenshot()->capture();
echo $capture->url;

// OR Stream Response
$capture = Capturely::url('https://google.com')->pdf()->stream();

return $capture;
```

### Usage Screenshot

```php
<?php

use Capturely\Capturely;
use Capturely\Structures\Screenshot;

$capture = Capturely::url('https://google.com')
    ->screenshot(function(Screenshot $screenshot) {
         return $screenshot
            ->fullPage()
            ->png()
            ->base64();
    })
    ->capture();

echo $capture->url;
```

### Additional Options

```php
<?php

use Capturely\Capturely;
use Capturely\Structures\Pdf;
use Capturely\Structures\Viewport;

$capture = Capturely::url('https://google.com')
    ->pdf(function(Pdf $pdf) {
         return $pdf->letter();
    })
    ->viewport(function(Viewport $viewport){
        return $viewport
                ->windowSize(800,600)
                ->isLandscape();
    })
    ->extraHttpHeaders([
        'Custom-Header-Name' => 'secret',
    ])
    ->authentication('user', 'pass')
    ->userAgent('My Custom User Agent')
    ->emulateMediaType('print')
    ->toS3('my-bucket', 'path')
    ->capture();

echo $capture->url;
```
