<?php

namespace App\Payments\Processors\Mobile;

use Laravel\Dusk\Browser as Dusk;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Laravel\Dusk\Chrome\SupportsChrome;
use Laravel\Dusk\Concerns\ProvidesBrowser;
use Illuminate\Support\Str;

class Browser
{
    use ProvidesBrowser, SupportsChrome;

    public function __construct()
    {
        Dusk::$storeScreenshotsAt = base_path('tests/Browser/screenshots');

        Dusk::$storeConsoleLogAt = base_path('tests/Browser/console');

        Dusk::$storeSourceAt = base_path('tests/Browser/source');

        static::useChromedriver(env('CHROMEDRIVER_PATH', base_path('vendor\laravel\dusk\bin')));

        static::startChromeDriver();
    }

    /**
     * Create the RemoteWebDriver instance.
     *
     * @return \Facebook\WebDriver\Remote\RemoteWebDriver
     */
     protected function driver()
     {
         $options = (new ChromeOptions)->addArguments([
             '--disable-gpu',
             '--headless',
             '--window-size=1920,1080',
             '--no-sandbox',
             '--log-level=3',
             '--silent'
         ])->setBinary(env('CHROME_PATH', 'C:\Program Files\Google\Chrome\Application\chrome.exe'));

         return RemoteWebDriver::create(
             'http://localhost:9515', DesiredCapabilities::chrome()->setCapability(
                 ChromeOptions::CAPABILITY, $options
             )
         );
     }

     /**
      *
      */
     public function makePayment($processor)
     {
         $this->browse(function ($browser) use ($processor){
             $browser->visit($processor->getVisitUrl())
                    ->type('authname', $processor->getClientName())
                    ->type('authphone', $processor->getPhone())
                    ->type('amount', $processor->getAmount())
                    ->type('reference', 'order')
                    ->click('.input-group.fifty');

            if ($browser->element('.btn.opt-btn')) {
                $browser->click('.btn.opt-btn');
            }

            $browser->click($processor->getSelector() . ' .payment-label .payment-selector')
                    ->click('p .btn')
                    ->type('phone', $processor->getPhone())
                    ->click('button[type=submit]')
                    ->pause(30000)
                    ->screenshot('after')
                    ->click('button[type=submit]');

            if (Str::of($browser->driver->getCurrentURL())->is('https://www.paynow.co.zw/Payment/ConfirmPayment/*')) {
                $processor->status = 'failed';
                return;
            }

            if (!(Str::of($browser->driver->getCurrentURL())->is('https://www.paynow.co.zw/Transaction/TransactionView*'))) {
                $processor->status = 'failed';
                return;
            }

            $processor->status = 'success';
            $details = [];

            foreach ($browser->elements('.trans-details p') as $element) {
                $html = $element->getAttribute('innerHTML');
                $value = Str::of($html)->after('</span>');
                $key = Str::between($html, '<span class="label">', '</span>');
                $key = Str::lower($key);
                $details[Str::slug($key, '_')] = $value;
            }

            $processor->details = $details;
         });
     }

     public function getName($why = true)
     {
         return 'Payments';
     }
}
