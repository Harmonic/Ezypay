<?php

namespace harmonic\Ezypay\Traits;

trait Merchant
{
    public function getMerchant()
    {
        $response = $this->request('GET', 'billing/merchant');

        return \harmonic\Ezypay\Resources\Merchant::make($response)->resolve();
    }
}
