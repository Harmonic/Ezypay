<?php

namespace harmonic\Ezypay\Testing;

use Illuminate\Support\Arr;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class EzypayFake {
    /**
     * The commands that should be intercepted instead of called to ezypay.
     *
     * @var array
     */
    protected $commandsToFake;

    /**
     * Faker object
     *
     * @var Faker
     */
    protected $faker;

    protected $token = null;
    private $tokenFile = 'ezypayToken.txt';

    /**
     * Create a new event fake instance.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $dispatcher
     * @param  array|string  $eventsToFake
     * @return void
     */
    public function __construct($commandsToFake = []) {
        Storage::fake('ezypayTest');
        $this->commandsToFake = Arr::wrap($commandsToFake);
        $this->faker = Faker::create();
        $this->token = $this->getAccessToken();
    }

    public function instance() {
        return $this;
    }

    public function getToken() {
        return $this->token;
    }

    public function createCreditCardPaymentMethod(string $accountHolderName, string $cardNumber, int $expiryMonth, int $expiryYear, string $country = 'AU') {
        $vault = [
          'accountHolderName' => $accountHolderName,
          'paymentMethodToken' => $this->faker->uuid,
          'card' => ['last4' => substr($cardNumber, -4)]
        ];

        return $vault;
    }

    public function getSubscription(string $subscriptionId) {
        $customerId = '93f3dee2-5424-4c06-be14-4fa6c2caa71b';
        return $this->createSubscription($customerId);
    }

    public function createSubscription(string $customerId, string $planId = null, string $paymentMethodToken = null, Carbon $startDate = null, bool $markAsPending = false, bool $customNotification = false) {
        $subscription = [
            'id' => '77d9f27f-fbfd-4d7a-9433-75841a4662dd',
            'customerId' => $customerId,
            'planId' => $planId ?? $this->faker->uuid,
            'name' => 'Share Link Bronze',
            'status' => 'FUTURE',
            'startDate' => $startDate ?? Carbon::now()->endOfYear()->format('Y-m-d'),
            'endDate' => null,
            'paymentMethodToken' => $paymentMethodToken ?? $this->faker->uuid,
            'accountingCode' => 'SLB',
            'amount' => [
              'currency' => 'AUD',
              'value' => 33.0,
            ],
            'tax' => [
              'rate' => 10.0,
            ],
            'nextBillingDate' => '2019-12-31',
            'nextFutureInvoice' => [
              'subscriptionId' => '77d9f27f-fbfd-4d7a-9433-75841a4662dd',
              'date' => '2019-12-31',
              'cycleStartDate' => '2019-12-31',
              'cycleEndDate' => null,
              'items' => [
                'data' => [
                  0 => [
                    'description' => 'Share Link Bronze',
                    'amount' => [
                      'currency' => 'AUD',
                      'value' => 1.06,
                    ],
                    'tax' => [
                      'rate' => 10.0,
                    ],
                    'id' => null,
                    'type' => 'subscription_payment',
                    'accountingCode' => 'SLB',
                  ],
                ],
                'links' => [
                  'self' => 'link-value',
                ],
              ],
              'amount' => [
                'currency' => 'AUD',
                'value' => 1.06,
              ],
              'totalTax' => [
                'currency' => 'AUD',
                'value' => 0.1,
              ],
            ],
            'interval' => 1,
            'intervalUnit' => 'MONTH',
            'totalPaid' => [
              'currency' => 'AUD',
              'value' => 0.0,
            ],
            'totalBillingCycles' => 0,
            'remainingToPay' => null,
            'remainingBillingCycles' => null,
            'endTargetAmount' => null,
            'endTargetBillingCycles' => null,
            'cancelledDate' => null,
            'failedPaymentHandling' => null,
            'failedAttemptsCount' => 0,
            'totalPastDue' => [
              'currency' => 'AUD',
              'value' => 0.0,
            ],
            'totalDiscounted' => [
              'currency' => 'AUD',
              'value' => 0.0,
            ],
            'metadata' => null,
            'createdOn' => '2019-01-24T06:17:05.963',
            'autoPayment' => true,
            'setupPayments' => null,
        ];

        return $subscription;
    }

    public function createPaymentMethod(string $customerId, string $vaultPaymentMethod, bool $primary = true) {
        $paymentMethod = [
          'id' => $this->faker->uuid,
          'paymentMethodToken' => $vaultPaymentMethod,
          'customerId' => $customerId,
          'card' => ['last4' => 2995],
          'primary' => $primary
        ];

        return $paymentMethod;
    }

    public function updateSubscription(string $subscriptionId, string $paymentMethodToken) {
        $subscription = [
          'id' => $subscriptionId,
          'paymentMethodToken' => $paymentMethodToken
        ];

        return $subscription;
    }

    public function replacePaymentMethod() {
        return [];
    }

    public function createCustomer(string $firstName = null, string $lastName = null, string $email = null, string $address1 = null, string $address2 = null, string $postCode = null, string $city = null, string $state = null, string $country = null, string $companyName = null, string $identifierType = null, int $identifierID = null) {
        $countryCode = substr($country ?? $this->faker->country,0,2);
        $customerDetails = [
            'id' => '93f3dee2-5424-4c06-be14-4fa6c2caa71b',
            'number' => 'EZY73520',
            'referenceCode' => null,
            'firstName' => $firstName ?? $this->faker->firstName(),
            'lastName' => $lastName ?? $this->faker->lastName,
            'email' => $email ?? $this->faker->email,
            'companyName' => $companyName ?? $this->faker->company,
            'mobilePhone' => null,
            'homePhone' => null,
            'gender' => null,
            'dateOfBirth' => null,
            'createdOn' => Carbon::now()->format('Y-m-d\TH:i:s.v'),
            'address' => [
              'address1' => $address1 ?? $this->faker->streetAddress,
              'address2' => $address2 ?? $this->faker->buildingNumber,
              'postalCode' => $postCode ?? $this->faker->randomNumber(4,true),
              'state' => $state ?? $this->faker->state,
              'countryCode' => $countryCode,
              'city' => $city ?? $this->faker->city,
            ],
            'metadata' => [
              'identifierType' => $identifierType ?? $this->faker->word,
              'identifierID' => $identifierID ?? $this->faker->randomNumber(3),
            ]
        ];

        return $customerDetails;
    }

    public function cancelSubscription(string $subscriptionId) {
        $subscription = [
          'id' => $subscriptionId,
          'status' => 'CANCELLED'
        ];

        return $subscription;
    }

    public function createBankPaymentMethod(string $accountHolderName, string $accountNumber, string $bsb, string $country = 'AU') {
        $vault = [
          'accountHolderName' => $accountHolderName,
          'paymentMethodToken' => $this->faker->uuid,
          'bank' => ['last4' => substr($accountNumber, -4)]
        ];

        return $vault;
    }

    public function getVaultPaymentMethodToken(string $token) {
        $vault = [
          'paymentMethodToken' => $this->faker->uuid
        ];

        return $vault;
    }

    public function getTransactions(bool $fetchAll = false, string $transactionNumber = null, string $senderId = null, string $documentId = null, int $limit = null, int $cursor = null, string $from = null, string $until = null, string $status = null) {
        $transactions = [
          [
            'id' => $this->faker->uuid,
            'status' => 'PROCESSING',
            'createdOn' => 1
          ]
        ];

        return $transactions;
    }

    public function getTransaction(string $transactionId) {
        $transaction = [
          'id' => $transactionId,
          'createdOn' => '12345'
        ];

        return $transaction;
    }

    public function activateSubscription(string $subscriptionId, string $startDate = null, string $paymentMethodToken = null) {
        $subscription = [
          'id' => $subscriptionId,
          'status' => 'active'
        ];

        return $subscription;
    }

    public function getSubscriptions() {
        $subscription = [
          [
            'id' => $this->faker->uuid,
            'customerId' => $this->faker->uuid,
            'planId' => $this->faker->uuid
          ]
        ];

        return $subscription;
    }

    public function previewSubscription(string $customerId, string $planId, string $paymentMethodToken = null, Carbon $startDate = null, bool $markAsPending = false) {
        $subscription = [
          'customerId' => $customerId,
          'name' => 'Share Link Bronze',
          'amount' => ['value' => 210]
        ];

        return $subscription;
    }

    public function groupSettlementReportByTransactionStatus(string $dateFrom = null, string $dateTo = null, string $documentType = null, array $merchantIds = []) {
        $settlementGroup = [
          'fileId' => 'Test123445',
          'documentType' => 'groupedby_transactionstatus'
        ];

        return $settlementGroup;
    }

    public function groupSettlementReportByAccountingCode(string $dateFrom = null, string $dateTo = null, string $documentType = null, array $merchantIds = []) {
        $settlementGroup = [
          'fileId' => 'Test1234',
          'documentType' => 'groupedby_accountingcode'
        ];

        return $settlementGroup;
    }

    public function getSettlements(bool $fetchAll = false, string $from = null, string $until = null, int $limit = null, int $cursor = null) {
        $settlements = [
          [
            'data' => []
          ]
        ];

        return $settlements;
    }

    public function createPlan(string $name, string $accountingCode, float $taxInclusiveAmt, string $status = 'active', float $taxRate = 10.00, string $intervalUnit = 'month', int $interval = 1, string $billingStart = 'day_of_month', string $billingEnd = 'ongoing', string $firstBilling = 'prorate', $metadata = null, string $memo = '', string $billingStartValue = '1') {
        $plan = [
          'name' => $name,
          'accountingCode' => $accountingCode,
          'id' => $this->faker->uuid
        ];

        return $plan;
    }

    public function getPlan(string $id) {
        $plan = [
          'id' => 'a9e5eb3e-0e82-4978-8a1a-eec8b9fc8306',
          'name' => 'Share Link Bronze',
          'accountingCode' => '12345678',
          'amount' => [
            'value' => 33.00
          ]
        ];

        return $plan;
    }

    public function getPlans(bool $fetchAll = false, int $limit = null, int $cursor = null, string $name = null, string $status = null) {
        $plan = [
          [
            'id' => 'a9e5eb3e-0e82-4978-8a1a-eec8b9fc8306',
            'name' => 'Share Link Bronze',
            'accountingCode' => '12345678',
            'amount' => [
              'value' => 33.00
            ]
          ],
          [
            'id' => '47af9b53-7497-43a8-b730-b3008e278caf',
            'name' => 'Share Link Silver',
            'accountingCode' => '12345678',
            'amount' => [
              'value' => 66.00
            ]
          ],
          [
            'id' => '53b295a3-aa61-4da6-b4e9-55f0a8c39cf9',
            'name' => 'Share Link Gold',
            'accountingCode' => '12345678',
            'amount' => [
              'value' => 99.00
            ]
          ]
        ];

        return $plan;
    }

    public function updatePlan(string $planId, string $name = null, string $accountingCode = null, float $taxInclusiveAmt = null, string $status = 'active', float $taxRate = 10.00, string $intervalUnit = 'month', int $interval = 1, string $billingStart = 'day_of_month', string $billingEnd = 'ongoing', string $firstBilling = 'prorate', $metadata = null, string $memo = '', string $billingStartValue = '1') {
        $plan = [
          'id' => $planId,
          'accountingCode' => $accountingCode,
          'name' => $name,
          'amount' => [
            'value' => 33.00
          ]
        ];

        return $plan;
    }

    public function getPrimaryPaymentMethod(string $customerId) {
        $paymentMethod = [
          'id' => $this->faker->uuid,
          'customerId' => $customerId,
          'primary' => true,
          'paymentMethodToken' => $this->faker->uuid,
          'type' => 'CARD',
          'lastUsedOn' => date('m/d'),
          'card' => [
            'last4' => $this->faker->numerify('####'),
            'type' => 'MASTERCARD'
          ]
        ];

        return $paymentMethod;
    }

    public function getPaymentMethod(string $customerId, string $paymentMethodToken) {
        $paymentMethod = [
          'customerId' => $customerId,
          'paymentMethodToken' => $paymentMethodToken
        ];

        return $paymentMethod;
    }

    public function deletePaymentMethodByCustomerId() {
        throw new \Exception('Unable to delete primary payment method');
    }

    public function getPaymentMethods(string $customerId, bool $fetchAll = false, int $limit = null, int $cursor = null) {
        return [
          'customerId' => $customerId
        ];
    }

    public function getMerchant() {
        $merchant = ['name' => 'Harmonic New Media Test'];

        return $merchant;
    }

    public function getInvoices(bool $fetchAll = false, string $customerId = null, string $subscriptionId = null, int $status = null, string $from = null, string $until = null, int $limit = null, int $cursor = null) {
        $invoices = [
          [
            'id' => $this->faker->uuid,
            'status' => isset($status) ? $status : 'WRITTEN_OFF',
            'documentNumber' => $this->faker->uuid,
            'date' => 'Test'
          ],
          [
            'id' => $this->faker->uuid,
            'status' => isset($status) ? $status : 'PROCESSING',
            'amount' => ['currency' => 'USD', 'value' => 1234],
            'documentNumber' => $this->faker->uuid,
            'date' => '2019-01-22'
          ]
        ];

        return $invoices;
    }

    public function getInvoice(string $invoiceId) {
        $invoice = [
          'id' => $invoiceId,
          'status' => 'PROCESSING',
          'amount' => ['currency' => 'USD', 'value' => 1234],
          'documentNumber' => $this->faker->uuid,
          'date' => '2019-01-22'
        ];

        return $invoice;
    }

    public function writeOffAnInvoice(string $invoiceId) {
        $invoices = [
          'id' => $invoiceId,
          'status' => 'WRITTEN_OFF'
        ];

        return $invoices;
    }

    public function createInvoice(string $customerId, array $items, string $paymentMethodToken = null, string $memo = null, bool $autoPayment = true, string $scheduledPaymentDate = null) {
        $invoices = [
          'id' => $this->faker->uuid,
          'customerId' => $customerId,
          'documentNumber' => $this->faker->uuid,
          'status' => 'WRITTEN_OFF',
          'date' => '2019-01-22'
        ];

        return $invoices;
    }

    public function retryPayment(string $invoiceId, bool $oneOff = false, string $paymentMethodToken = null) {
        $invoices = [
          'id' => $invoiceId,
          'status' => 'PROCESSING'
        ];

        return $invoices;
    }

    public function recordExternalPayment(string $invoiceId, string $paymentMethodType = null) {
        $payment = [
          'id' => $invoiceId,
          'status' => 'PAID',
          'paymentMethodType' => $paymentMethodType
        ];

        return $payment;
    }

    public function getFutureInvoice(string $subscriptionId, string $customerId, string $from, string $until, int $limit = null, bool $fetchAll = false) {
        $futureInvoice = [
          [
            'subscriptionId' => $subscriptionId,
            'customerId' => $customerId,
            'cycleStartDate' => '123'
          ]
        ];

        return $futureInvoice;
    }

    public function deleteFutureInvoice() {
        $futureInvoice = ['deleted' => true];

        return $futureInvoice;
    }

    public function updateFutureInvoice(string $subscriptionId, string $cycleStartDate, string $date, array $items = []) {
        return [
          'subscriptionId' => $subscriptionId
        ];
    }

    public function createFutureInvoice(string $subscriptionId, string $cycleStartDate, string $paymentMethodType) {
        $futureInvoice = [
          'subscriptionId' => $subscriptionId,
          'cycleStartDate' => $cycleStartDate,
          'paymentMethodType' => $paymentMethodType,
          'status' => 'PAID'
        ];

        return $futureInvoice;
    }

    public function refundInvoice(string $invoiceId, string $amountCurrency, int $amountValue, array $items = []) {
        $invoices = [
          'id' => $invoiceId,
          'status' => 'PROCESSING'
        ];

        return $invoices;
    }

    public function getCustomers(
      bool $fetchAll = false,
      string $name = null,
      string $firstName = null,
      string $lastName = null,
      string $companyName = null,
      string $referenceCode = null,
      string $customerNumber = null,
      string $createdDate = null,
      string $sortExpression = null,
      int $limit = null,
      int $cursor = null
    ) {
        $customers = [
          [
            'id' => '93f3dee2-5424-4c06-be14-4fa6c2caa71b',
            'number' => 'EZY73520',
            'referenceCode' => null,
            'firstName' => $this->faker->firstName(),
            'lastName' => $this->faker->lastName,
            'email' => $this->faker->email,
            'companyName' => $this->faker->company,
            'mobilePhone' => null,
            'homePhone' => null,
            'gender' => null,
            'dateOfBirth' => null,
            'createdOn' => '2019-01-24T04:32:14.855',
            'address' => [
              'address1' => $this->faker->streetAddress,
              'address2' => $this->faker->buildingNumber,
              'postalCode' => $this->faker->randomNumber(4,true),
              'state' => $this->faker->state,
              'countryCode' => $this->faker->countryCode,
              'city' => $this->faker->city,
            ],
            'metadata' => [
              'identifierType' => $this->faker->word,
              'identifierID' => $this->faker->randomNumber(3),
            ]
          ],
          'fetchAll' => $fetchAll,
          'limit' => $limit,
          'cursor', $cursor
        ];

        return $customers;
    }

    public function getCustomer(string $customerId) {
        $customer = [
          'id' => $customerId,
          'number' => 'EZY73520',
          'referenceCode' => null,
          'firstName' => $this->faker->firstName(),
          'lastName' => $this->faker->lastName,
          'email' => $this->faker->email,
          'companyName' => $this->faker->company,
          'mobilePhone' => null,
          'homePhone' => null,
          'gender' => null,
          'dateOfBirth' => null,
          'createdOn' => '2019-01-24T04:32:14.855',
          'address' => [
            'address1' => $this->faker->streetAddress,
            'address2' => $this->faker->buildingNumber,
            'postalCode' => $this->faker->randomNumber(4,true),
            'state' => $this->faker->state,
            'countryCode' => $this->faker->countryCode,
            'city' => $this->faker->city,
          ],
          'metadata' => [
            'identifierType' => $this->faker->word,
            'identifierID' => $this->faker->randomNumber(3),
          ]
        ];

        return $customer;
    }

    public function updateCustomer(
      string $customerId,
      string $email,
      string $firstName,
      string $lastName,
      string $address1,
      string $companyName = null,
      string $gender = null,
      string $homePhone = null,
      string $mobilePhone = null,
      string $referenceCode = null,
      string $dateOfBirth = null,
      string $address2 = null,
      string $postCode = null,
      string $city = null,
      string $state = null,
      string $countryCode = null
    ) {
        $countryCode = substr($country ?? $this->faker->country,0,2);
        $customer = [
          'id' => $customerId,
          'number' => 'EZY73520',
          'referenceCode' => null,
          'firstName' => $firstName ?? $this->faker->firstName(),
          'lastName' => $lastName ?? $this->faker->lastName,
          'email' => $email ?? $this->faker->email,
          'companyName' => $companyName ?? $this->faker->company,
          'mobilePhone' => null,
          'homePhone' => null,
          'gender' => null,
          'dateOfBirth' => null,
          'createdOn' => '2019-01-24T04:32:14.855',
          'address' => [
            'address1' => $address1 ?? $this->faker->streetAddress,
            'address2' => $address2 ?? $this->faker->buildingNumber,
            'postalCode' => $postCode ?? $this->faker->randomNumber(4,true),
            'state' => $state ?? $this->faker->state,
            'countryCode' => $countryCode,
            'city' => $city ?? $this->faker->city,
          ],
          'metadata' => [
            'identifierType' => $identifierType ?? $this->faker->word,
            'identifierID' => $identifierID ?? $this->faker->randomNumber(3),
          ]
        ];

        return $customer;
    }

    public function getCreditNotes(
      string $customerId,
      string $subscriptionId = null,
      string $invoiceId = null,
      bool $fetchAll = false,
      string $status = null,
      string $reason = null,
      string $from = null,
      string $until = null,
      int $limit = null,
      int $cursor = null
    ) {
        $filters = [
          'customerId' => $customerId,
          'subscriptionId' => $subscriptionId,
          'invoiceId' => $invoiceId,
          'status' => $status,
          'reason' => $reason,
          'from' => $from,
          'until' => $until,
          'limit' => $limit,
          'cursor' => $cursor
        ];

        $notes = [
          [
            'customer_id' => $customerId,
            'id' => $this->faker->uuid,
            'fetchAll' => $fetchAll
          ]
        ];

        return $notes;
    }

    public function getCreditNote(string $creditNoteId) {
        $note = [
          'id' => $creditNoteId
        ];

        return $note;
    }

    private function requestToken(String $refreshToken = null) {
        $tokenObj = [
          'refresh_token' => $this->faker->uuid,
          'access_token' => $this->faker->uuid
        ];

        $tokenObj['expiration'] = Carbon::now()->addSeconds(3590); // Just under hour
        Storage::disk('ezypayTest')->put($this->tokenFile, json_encode($tokenObj));

        return $tokenObj;
    }

    private function getAccessToken() {
        try {
            $tokenDataFile = Storage::disk('ezypayTest')->get($this->tokenFile);
        } catch (\Illuminate\Contracts\Filesystem\FileNotFoundException $e) {
            $tokenData = $this->requestToken();
            return $tokenData['access_token'];
        }

        $tokenData = json_decode($tokenDataFile, true);

        $now = Carbon::now();
        $tokenIsExpired = ($now->gte(new Carbon($tokenData['expiration']['date'])));

        if ($tokenIsExpired) {
            $tokenData = $this->requestToken($tokenData['refresh_token']);
        }

        return $tokenData['access_token'];
    }
}
