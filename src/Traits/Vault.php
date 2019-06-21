<?php

namespace harmonic\Ezypay\Traits;

trait Vault {
    /**
     * Create a direct debit bank payment method via the vault (needs assigning to customer)
     *
     * @param string $accountHolderName
     * @param string $accountNumber
     * @param string $bsb
     * @param string $country
     * @return void
     */
    public function createBankPaymentMethod(string $accountHolderName, string $accountNumber, string $bsb, string $country = 'AU') {
        $data = [
            'accountHolderName' => $accountHolderName,
            'accountNumber' => $accountNumber,
            'bankNumber' => $bsb,
            'countryCode' => strtoupper($country),
            'termAndConditionAgreed' => true
        ];

		$response = $this->request('POST', 'vault/paymentmethodtokens/bank', $data);
        return \harmonic\Ezypay\Resources\Vault::make($response)->resolve();
    }

    /**
     * Create a credit card payment method cia vault (needs assigning to customer)
     *
     * @param string $accountHolderName
     * @param string $cardNumber
     * @param integer $expiryMonth
     * @param integer $expiryYear
     * @param string $country
     * @return void
     */
    public function createCreditCardPaymentMethod(string $accountHolderName, string $cardNumber, int $expiryMonth, int $expiryYear, string $country = 'AU') {
        $data = [
            'accountHolderName' => $accountHolderName,
            'accountNumber' => $cardNumber,
            'countryCode' => strtoupper($country),
            'expiryMonth' => (string) $expiryMonth,
            'expiryYear' => (string) $expiryYear,
            'termAndConditionAgreed' => true
        ];

        $response = $this->request('POST', 'vault/paymentmethodtokens/card', $data);
        return \harmonic\Ezypay\Resources\Vault::make($response)->resolve();
    }

    /**
     * Retrieve a payment method token
     *
     * @param string $token
     * @return void
     */
    public function getVaultPaymentMethodToken(string $token) {
		$response = $this->request('GET', 'vault/paymentmethodtokens/' . $token);
		return \harmonic\Ezypay\Resources\Vault::make($response)->resolve();
    }
}
