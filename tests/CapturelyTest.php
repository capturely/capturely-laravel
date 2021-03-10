<?php

namespace Capturely\Tests;

use Capturely\Capturely;
use Capturely\CapturelyServiceProvider;
use Capturely\Exceptions\CapturelyTokenNotSet;
use Capturely\Structures\Pdf;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Orchestra\Testbench\TestCase;

class CapturelyTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [CapturelyServiceProvider::class];
    }


    public function test_pdf_capturely_functionality() : void
    {
        config(['services.capturely.token' => '1|1234']);

        Http::fake([
            'storage.capturely.app/*' => Http::response('data'),

            'api.capturely.app*' => Http::response([
                'url' => 'https://storage.capturely.app/2021-02-19/dcb144e7-3eda-47f9-8c32-e05c098cd5e3.png',
                'size' => 8883,
            ]),
        ]);

        $capture = Capturely::html(now()->toDateTimeLocalString())
            ->pdf(function(Pdf $pdf) {
                return $pdf
                    ->scale(1.2)
                    ->preferCSSPageSize()
                    ->margin(1, 2, 3, 4)
                    ->letter();
            })
            ->capture();

        Http::assertSent(function(Request $request) {
            return $request->hasHeader('Authorization', '1|1234');
        });

        $this->assertEquals($capture->url, 'https://storage.capturely.app/2021-02-19/dcb144e7-3eda-47f9-8c32-e05c098cd5e3.png');
        $this->assertEquals($capture->size, 8883);
    }

    public function test_when_token_not_set_exception_is_thrown() : void
    {
        $this->expectException(CapturelyTokenNotSet::class);

        Capturely::html('a')
            ->pdf()
            ->capture();
    }

}
