<?php

namespace harmonic\Ezypay\Testing;

use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class EzypayFake
{
    /**
     * The commands that should be intercepted instead of called to ezypay.
     *
     * @var array
     */
    protected $commandsToFake;

    /**
     * Faker object.
     *
     * @var Faker
     */
    protected $faker;

    protected $token = null;

    /**
     * Create a new event fake instance.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $dispatcher
     * @param  array|string  $eventsToFake
     * @return void
     */
    public function __construct($commandsToFake = [])
    {
        Storage::fake('ezypayTest');
        $this->commandsToFake = Arr::wrap($commandsToFake);
        $this->faker = Faker::create();
        $this->token = $this->getAccessToken();
    }

    public function instance()
    {
        return $this;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function createCreditCardPaymentMethod(string $accountHolderName, string $cardNumber, int $expiryMonth, int $expiryYear, string $country = 'AU')
    {
        $vault = [
        'type' => 'CARD',
        'card' => [
          'accountHolderName' => $accountHolderName,
          'last4' => substr($cardNumber, -4),
          'expiryYear' => $this->faker->numerify('##'),
          'expiryMonth' => $this->faker->numerify('##'),
          'type' => 'VISA',
          'first6' => $this->faker->numerify('######'),
          'countryCode' => 'AU',
        ],
        'paymentMethodToken' => $this->faker->uuid,
      ];

        return $vault;
    }

    public function getSubscription(string $subscriptionId)
    {
        $customerId = '93f3dee2-5424-4c06-be14-4fa6c2caa71b';

        return $this->createSubscription($customerId);
    }

    public function createSubscription(string $customerId, string $planId = null, string $paymentMethodToken = null, Carbon $startDate = null, bool $markAsPending = false, bool $customNotification = false)
    {
        $subscription = [
            'id' => '77d9f27f-fbfd-4d7a-9433-75841a4662dd',
            'customerId' => $customerId,
            'planId' => $planId ?? $this->faker->uuid,
            'name' => 'Share Link Bronze',
            'status' => 'FUTURE',
            'startDate' => $startDate ?? Carbon::now()->addDays(config('ezypay.trial_days'))->toDateTimeString(),
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

    public function createPaymentMethod(string $customerId, string $vaultPaymentMethod, bool $primary = true)
    {
        $paymentMethod = [
          'paymentMethodToken' => $vaultPaymentMethod,
          'customerId' => $customerId,
          'type' => 'CARD',
          'bank' => null,
          'card' => [
            'first6' => $this->faker->numerify('######'),
            'last4' => $this->faker->numerify('####'),
            'accountHolderName' => $this->faker->firstName(),
            'type' => 'VISA',
            'expiryMonth' => '12',
            'expiryYear' => '25',
          ],
          'invalidReason' => null,
          'lastUsedOn' => null,
          'valid' => true,
          'primary' => true,
        ];

        return $paymentMethod;
    }

    public function updateSubscription(string $subscriptionId, string $paymentMethodToken)
    {
        $subscription = [
        'id' => $subscriptionId,
        'customerId' => $this->faker->uuid,
        'planId' => $this->faker->uuid,
        'name' => 'Share Link Bronze',
        'status' => 'CANCELLED',
        'startDate' => Carbon::now()->addDays(config('ezypay.trial_days'))->toDateTimeString(),
        'endDate' => null,
        'paymentMethodToken' => $paymentMethodToken,
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

    public function replacePaymentMethod()
    {
        return [];
    }

    public function createCustomer(string $firstName = null, string $lastName = null, string $email = null, string $address1 = null, string $address2 = null, string $postCode = null, string $city = null, string $state = null, string $country = null, string $companyName = null, string $identifierType = null, int $identifierID = null)
    {
        $countryCode = substr($country ?? $this->faker->country, 0, 2);
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
              'postalCode' => $postCode ?? $this->faker->randomNumber(4, true),
              'state' => $state ?? $this->faker->state,
              'countryCode' => $countryCode,
              'city' => $city ?? $this->faker->city,
            ],
            'metadata' => [
              'identifierType' => $identifierType ?? $this->faker->word,
              'identifierID' => $identifierID ?? $this->faker->randomNumber(3),
            ],
        ];

        return $customerDetails;
    }

    public function cancelSubscription(string $subscriptionId)
    {
        $subscription = [
          'id' => $subscriptionId,
          'customerId' => $this->faker->uuid,
          'planId' => $this->faker->uuid,
          'name' => 'Share Link Bronze',
          'status' => 'CANCELLED',
          'startDate' => Carbon::now()->addDays(config('ezypay.trial_days'))->toDateTimeString(),
          'endDate' => null,
          'paymentMethodToken' => $this->faker->uuid,
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

    public function createBankPaymentMethod(string $accountHolderName, string $accountNumber, string $bsb, string $country = 'AU')
    {
        $vault = [
          'type' => 'BANK',
          'bank' => [
            'accountHolderName' => $accountHolderName,
            'last4' => substr($accountNumber, -4),
            'bankNumber' => $this->faker->numerify('######'),
            'branchNumber' => $this->faker->numerify('####'),
            'suffixNumber' => $this->faker->numerify('##'),
            'countryCode' => 'AU',
            'bankTransferType' => 'Local',
          ],
          'paymentMethodToken' => $this->faker->uuid,
        ];

        return $vault;
    }

    public function getVaultPaymentMethodToken(string $token)
    {
        $accountNumber = $this->faker->randomNumber(9);
        $vault = [
        'type' => 'BANK',
        'bank' => [
          'accountHolderName' => $this->faker->firstName(),
          'last4' => substr($accountNumber, -4),
          'bankNumber' => $this->faker->numerify('######'),
          'branchNumber' => $this->faker->numerify('####'),
          'suffixNumber' => $this->faker->numerify('##'),
          'countryCode' => 'AU',
          'bankTransferType' => 'Local',
        ],
        'paymentMethodToken' => $token,
      ];

        return $vault;
    }

    public function getTransactions(bool $fetchAll = false, string $transactionNumber = null, string $senderId = null, string $documentId = null, int $limit = null, int $cursor = null, string $from = null, string $until = null, string $status = null)
    {
        $transactions = [
        'data' => [
          [
            'id' => $this->faker->uuid,
            'number' => null,
            'status' => $status ?? 'SUCCESS',
            'createdOn' => '2019-07-01T03:24:05.555',
            'failedOn' => null,
            'amount' => [
              'currency' => 'AUD',
              'value' => 33.0,
              'type' => 'FIXED_AMOUNT',
            ],
            'type' => 'PAYMENT',
            'source' => 'wallet',
            'paymentMethodType' => null,
            'paymentMethodDescription' => null,
            'failedPaymentReason' => null,
            'paymentProviderResponse' => [
              'code' => null,
              'description' => null,
            ],
            'document' => [
              'id' => $this->faker->uuid,
              'number' => null,
              'type' => 'invoice',
            ],
            'sender' => [
              'id' => $this->faker->uuid,
              'name' => $this->faker->word,
              'type' => 'MERCHANT',
            ],
            'receiver' => [
              'id' => null,
              'name' => 'EZYPAY',
              'type' => 'EZYPAY',
            ],
            'channel' => 'api',
          ],
        ],
        'paging' => [
          'nextUrl' => null,
          'nextCursor' => 0,
          'limit' => 0,
          'totalCount' => 1,
        ],
      ];

        return $transactions;
    }

    public function getTransaction(string $transactionId)
    {
        $transaction = [
          'id' => $transactionId,
          'number' => null,
          'status' => 'SUCCESS',
          'createdOn' => '2019-07-01T03:24:05.555',
          'failedOn' => null,
          'amount' => [
            'currency' => 'AUD',
            'value' => 33.0,
            'type' => 'FIXED_AMOUNT',
          ],
          'type' => 'PAYMENT',
          'source' => 'wallet',
          'paymentMethodType' => null,
          'paymentMethodDescription' => null,
          'failedPaymentReason' => null,
          'paymentProviderResponse' => [
            'code' => null,
            'description' => null,
          ],
          'document' => [
            'id' => $this->faker->uuid,
            'number' => null,
            'type' => 'invoice',
          ],
          'sender' => [
            'id' => $this->faker->uuid,
            'name' => $this->faker->word,
            'type' => 'MERCHANT',
          ],
          'receiver' => [
            'id' => null,
            'name' => 'EZYPAY',
            'type' => 'EZYPAY',
          ],
          'channel' => 'api',
        ];

        return $transaction;
    }

    public function activateSubscription(string $subscriptionId, string $startDate = null, string $paymentMethodToken = null)
    {
        $subscription = [
        'id' => $subscriptionId,
        'customerId' => $this->faker->uuid,
        'planId' => $this->faker->uuid,
        'name' => 'Share Link Bronze',
        'status' => 'ACTIVE',
        'startDate' => Carbon::now()->addDays(config('ezypay.trial_days'))->toDateTimeString(),
        'endDate' => null,
        'paymentMethodToken' => $this->faker->uuid,
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

    public function getSubscriptions()
    {
        $subscriptions = [
          'data' => [
            [
              'id' => $this->faker->uuid,
              'customerId' => $this->faker->uuid,
              'planId' => $this->faker->uuid,
              'name' => 'Share Link Bronze',
              'status' => 'FUTURE',
              'startDate' => Carbon::now()->addDays(config('ezypay.trial_days'))->toDateTimeString(),
              'endDate' => null,
              'paymentMethodToken' => $this->faker->uuid,
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
            ],
          ],
          'paging' => [
            'nextUrl' => null,
            'nextCursor' => 0,
            'limit' => 0,
            'totalCount' => 1,
          ],
        ];

        return $subscriptions;
    }

    public function previewSubscription(string $customerId, string $planId, string $paymentMethodToken = null, Carbon $startDate = null, bool $markAsPending = false)
    {
        $subscription = [
          'data' => [
            [
              'id' => $this->faker->uuid,
              'customerId' => $customerId,
              'planId' => $planId ?? $this->faker->uuid,
              'name' => 'Share Link Bronze',
              'status' => 'FUTURE',
              'startDate' => Carbon::now()->addDays(config('ezypay.trial_days'))->toDateTimeString(),
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
            ],
          ],
          'paging' => [
            'nextUrl' => null,
            'nextCursor' => 0,
            'limit' => 0,
            'totalCount' => 1,
          ],
        ];

        return $subscription;
    }

    public function groupSettlementReportByTransactionStatus(string $dateFrom = null, string $dateTo = null, string $documentType = null, array $merchantIds = [])
    {
        $settlementGroup = [
          'fileId' => 'Test123445',
          'documentType' => 'groupedby_transactionstatus',
        ];

        return $settlementGroup;
    }

    public function groupSettlementReportByAccountingCode(string $dateFrom = null, string $dateTo = null, string $documentType = null, array $merchantIds = [])
    {
        $settlementGroup = [
          'fileId' => 'Test1234',
          'documentType' => 'groupedby_accountingcode',
        ];

        return $settlementGroup;
    }

    public function getSettlements(bool $fetchAll = false, string $from = null, string $until = null, int $limit = null, int $cursor = null)
    {
        $settlements = [
          [
            'data' => [],
          ],
        ];

        return $settlements;
    }

    public function createPlan(string $name, string $accountingCode, float $taxInclusiveAmt, string $status = 'active', float $taxRate = 10.00, string $intervalUnit = 'month', int $interval = 1, string $billingStart = 'day_of_month', string $billingEnd = 'ongoing', string $firstBilling = 'prorate', $metadata = null, string $memo = '', string $billingStartValue = '1')
    {
        $plan = [
          'id' => $this->faker->uuid,
          'status' => 'ACTIVE',
          'name' => $name,
          'memo' => null,
          'accountingCode' => $accountingCode,
          'amount' => [
              'currency' => 'AUD',
              'value' => 200.00,
              'type' => null,
          ],
          'tax' => [
              'rate' => 0.00,
          ],
          'setupPayments' => null,
          'intervalUnit' => 'MONTH',
          'interval' => 1,
          'billingStart' => 'DAY_OF_MONTH',
          'billingStartValue' => '1',
          'billingEnd' => 'ONGOING',
          'billingEndValue' => null,
          'firstBilling' => 'FULL_AMOUNT',
          'recurringBillingDay' => null,
          'failedPaymentHandling' => [
              'initialAction' => 'STOP',
              'autoRetry' => true,
              'retryInDays' => 3,
              'maximumFailedAttempts' => 4,
          ],
          'metadata' => null,
          'createdOn' => '2019-03-11T09:10:53.771',
        ];

        return $plan;
    }

    public function getPlan(string $id)
    {
        $plan = [
          'id' => $this->faker->uuid,
          'status' => 'ACTIVE',
          'name' => 'Share Link Bronze',
          'memo' => null,
          'accountingCode' => $this->faker->word,
          'amount' => [
              'currency' => 'AUD',
              'value' => $this->faker->randomNumber(3),
              'type' => null,
          ],
          'tax' => [
              'rate' => 0.00,
          ],
          'setupPayments' => null,
          'intervalUnit' => 'MONTH',
          'interval' => 1,
          'billingStart' => 'DAY_OF_MONTH',
          'billingStartValue' => '1',
          'billingEnd' => 'ONGOING',
          'billingEndValue' => null,
          'firstBilling' => 'FULL_AMOUNT',
          'recurringBillingDay' => null,
          'failedPaymentHandling' => [
              'initialAction' => 'STOP',
              'autoRetry' => true,
              'retryInDays' => 3,
              'maximumFailedAttempts' => 4,
          ],
          'metadata' => null,
          'createdOn' => '2019-03-11T09:10:53.771',
        ];

        return $plan;
    }

    public function getPlans(bool $fetchAll = false, int $limit = null, int $cursor = null, string $name = null, string $status = null)
    {
        $plan = [
          'data' => [
            [
              'id' => $this->faker->uuid,
              'status' => 'ACTIVE',
              'name' => 'Share Link Bronze',
              'memo' => null,
              'accountingCode' => $this->faker->word,
              'amount' => [
                  'currency' => 'AUD',
                  'value' => $this->faker->randomNumber(3),
                  'type' => null,
              ],
              'tax' => [
                  'rate' => 0.00,
              ],
              'setupPayments' => null,
              'intervalUnit' => 'MONTH',
              'interval' => 1,
              'billingStart' => 'DAY_OF_MONTH',
              'billingStartValue' => '1',
              'billingEnd' => 'ONGOING',
              'billingEndValue' => null,
              'firstBilling' => 'FULL_AMOUNT',
              'recurringBillingDay' => null,
              'failedPaymentHandling' => [
                  'initialAction' => 'STOP',
                  'autoRetry' => true,
                  'retryInDays' => 3,
                  'maximumFailedAttempts' => 4,
              ],
              'metadata' => null,
              'createdOn' => '2019-03-11T09:10:53.771',
            ],
          ],
          'paging' => [
            'nextUrl' => null,
            'nextCursor' => 0,
            'limit' => 0,
            'totalCount' => 1,
          ],
        ];

        return $plan;
    }

    public function updatePlan(string $planId, string $name = null, string $accountingCode = null, float $taxInclusiveAmt = null, string $status = 'active', float $taxRate = 10.00, string $intervalUnit = 'month', int $interval = 1, string $billingStart = 'day_of_month', string $billingEnd = 'ongoing', string $firstBilling = 'prorate', $metadata = null, string $memo = '', string $billingStartValue = '1')
    {
        $plan = [
          'id' => $planId,
          'status' => 'ACTIVE',
          'name' => $name,
          'memo' => null,
          'accountingCode' => $accountingCode,
          'amount' => [
              'currency' => 'AUD',
              'value' => $taxInclusiveAmt,
              'type' => null,
          ],
          'tax' => [
              'rate' => 0.00,
          ],
          'setupPayments' => null,
          'intervalUnit' => 'MONTH',
          'interval' => 1,
          'billingStart' => 'DAY_OF_MONTH',
          'billingStartValue' => '1',
          'billingEnd' => 'ONGOING',
          'billingEndValue' => null,
          'firstBilling' => 'FULL_AMOUNT',
          'recurringBillingDay' => null,
          'failedPaymentHandling' => [
              'initialAction' => 'STOP',
              'autoRetry' => true,
              'retryInDays' => 3,
              'maximumFailedAttempts' => 4,
          ],
          'metadata' => null,
          'createdOn' => '2019-03-11T09:10:53.771',
        ];

        return $plan;
    }

    public function getPrimaryPaymentMethod(string $customerId)
    {
        $paymentMethod = [
          'paymentMethodToken' => $this->faker->uuid,
          'customerId' => $customerId,
          'type' => 'CARD',
          'bank' => null,
          'card' => [
            'first6' => $this->faker->numerify('######'),
            'last4' => $this->faker->numerify('####'),
            'accountHolderName' => $this->faker->firstName(),
            'type' => 'VISA',
            'expiryMonth' => '12',
            'expiryYear' => '25',
          ],
          'invalidReason' => null,
          'lastUsedOn' => null,
          'valid' => true,
          'primary' => true,
        ];

        return $paymentMethod;
    }

    public function getPaymentMethod(string $customerId, string $paymentMethodToken)
    {
        $paymentMethod = [
          'paymentMethodToken' => $paymentMethodToken,
          'customerId' => $customerId,
          'type' => 'CARD',
          'bank' => null,
          'card' => [
            'first6' => $this->faker->numerify('######'),
            'last4' => $this->faker->numerify('####'),
            'accountHolderName' => $this->faker->firstName(),
            'type' => 'VISA',
            'expiryMonth' => '12',
            'expiryYear' => '25',
          ],
          'invalidReason' => null,
          'lastUsedOn' => null,
          'valid' => true,
          'primary' => false,
        ];

        return $paymentMethod;
    }

    public function deletePaymentMethodByCustomerId()
    {
        throw new \Exception('Unable to delete primary payment method');
    }

    public function getPaymentMethods(string $customerId, bool $fetchAll = false, int $limit = null, int $cursor = null)
    {
        return [
          'data' => [
            [
              'paymentMethodToken' => $this->faker->uuid,
              'customerId' => $customerId,
              'type' => 'CARD',
              'bank' => null,
              'card' => [
                  'first6' => $this->faker->numerify('######'),
                  'last4' => $this->faker->numerify('####'),
                  'accountHolderName' => $this->faker->firstName(),
                  'type' => 'VISA',
                  'expiryMonth' => '01',
                  'expiryYear' => '99',
              ],
              'invalidReason' => null,
              'lastUsedOn' => null,
              'valid' => true,
              'primary' => true,
            ],
          ],
          'paging' => [
            'nextUrl' => null,
            'nextCursor' => 0,
            'limit' => 0,
            'totalCount' => 1,
          ],
        ];
    }

    public function getMerchant()
    {
        $merchant = ['name' => 'Harmonic New Media Test'];

        return $merchant;
    }

    public function getInvoices(bool $fetchAll = false, string $customerId = null, string $subscriptionId = null, string $status = null, string $from = null, string $until = null, int $limit = null, int $cursor = null)
    {
        $invoices = [
          'data' => [
            [
              'id' => $this->faker->uuid,
              'documentNumber' => 'IN0000000000000628',
              'date' => '2019-07-01',
              'dueDate' => '2019-07-01',
              'scheduledPaymentDate' => null,
              'status' => isset($status) ? $status : 'WRITTEN_OFF',
              'memo' => null,
              'items' => [
                [
                  'description' => 'Share Link Bronze',
                  'amount' => [
                    'currency' => 'AUD',
                    'value' => 1.06,
                  ],
                  'tax' => [
                    'rate' => 10.0,
                  ],
                  'id' => $this->faker->uuid,
                  'type' => 'subscription_payment',
                  'accountingCode' => 'SLB',
                ],
              ],
              'amount' => [
                'currency' => 'AUD',
                'value' => 200,
                'type' => null,
              ],
              'amountWithoutDiscount' => [
                'currency' => 'AUD',
                'value' => 0,
                'type' => null,
              ],
              'totalDiscounted' => [
                'currency' => 'AUD',
                'value' => 0,
                'type' => null,
              ],
              'totalRefunded' => [
                'currency' => 'AUD',
                'value' => 0,
                'type' => null,
              ],
              'totalTax' => [
                'currency' => 'AUD',
                'value' => 0,
                'type' => null,
              ],
              'customerId' => $this->faker->uuid,
              'subscriptionId' => null,
              'subscriptionName' => null,
              'paymentMethodToken' => $this->faker->uuid,
              'autoPayment' => true,
              'createdOn' => '2019-07-01T00:33:42.562',
            ],
          ],
          'paging' => [
            'nextUrl' => null,
            'nextCursor' => 0,
            'limit' => 0,
            'totalCount' => 1,
          ],
        ];

        return $invoices;
    }

    public function getInvoice(string $invoiceId)
    {
        $invoice = [
          'id' => $invoiceId,
          'status' => 'PROCESSING',
          'amount' => ['currency' => 'USD', 'value' => 1234],
          'documentNumber' => $this->faker->uuid,
          'date' => '2019-01-22',
        ];

        return $invoice;
    }

    public function writeOffAnInvoice(string $invoiceId)
    {
        $invoices = [
          'id' => $invoiceId,
          'status' => 'WRITTEN_OFF',
        ];

        return $invoices;
    }

    public function createInvoice(string $customerId, array $items, string $paymentMethodToken = null, string $memo = null, bool $autoPayment = true, string $scheduledPaymentDate = null)
    {
        $invoice = [
          'id' => $this->faker->uuid,
          'documentNumber' => 'IN0000000000000628',
          'date' => '2019-07-01',
          'dueDate' => '2019-07-01',
          'scheduledPaymentDate' => null,
          'status' => 'PROCESSING',
          'memo' => null,
          'items' => $items,
          'amount' => [
            'currency' => 'AUD',
            'value' => 200,
            'type' => null,
          ],
          'amountWithoutDiscount' => [
            'currency' => 'AUD',
            'value' => 0,
            'type' => null,
          ],
          'totalDiscounted' => [
            'currency' => 'AUD',
            'value' => 0,
            'type' => null,
          ],
          'totalRefunded' => [
            'currency' => 'AUD',
            'value' => 0,
            'type' => null,
          ],
          'totalTax' => [
            'currency' => 'AUD',
            'value' => 0,
            'type' => null,
          ],
          'customerId' => $customerId,
          'subscriptionId' => null,
          'subscriptionName' => null,
          'paymentMethodToken' => $paymentMethodToken,
          'autoPayment' => true,
          'createdOn' => '2019-07-01T00:33:42.562',
        ];

        return $invoice;
    }

    public function retryPayment(string $invoiceId, bool $oneOff = false, string $paymentMethodToken = null)
    {
        $invoices = [
          'id' => $invoiceId,
          'status' => 'PROCESSING',
        ];

        return $invoices;
    }

    public function recordExternalPayment(string $invoiceId, string $paymentMethodType = null)
    {
        $payment = [
          'id' => $invoiceId,
          'documentNumber' => 'IN0000000000000628',
          'date' => '2019-07-01',
          'dueDate' => '2019-07-01',
          'scheduledPaymentDate' => null,
          'status' => 'PROCESSING',
          'memo' => null,
          'items' => [
            [
              'description' => 'Share Link Bronze',
              'amount' => [
                'currency' => 'AUD',
                'value' => 1.06,
              ],
              'tax' => [
                'rate' => 10.0,
              ],
              'id' => $this->faker->uuid,
              'type' => 'subscription_payment',
              'accountingCode' => 'SLB',
            ],
          ],
          'amount' => [
            'currency' => 'AUD',
            'value' => 200,
            'type' => null,
          ],
          'amountWithoutDiscount' => [
            'currency' => 'AUD',
            'value' => 0,
            'type' => null,
          ],
          'totalDiscounted' => [
            'currency' => 'AUD',
            'value' => 0,
            'type' => null,
          ],
          'totalRefunded' => [
            'currency' => 'AUD',
            'value' => 0,
            'type' => null,
          ],
          'totalTax' => [
            'currency' => 'AUD',
            'value' => 0,
            'type' => null,
          ],
          'customerId' => $this->faker->uuid,
          'subscriptionId' => null,
          'subscriptionName' => null,
          'paymentMethodToken' => $this->faker->uuid,
          'autoPayment' => true,
          'createdOn' => '2019-07-01T00:33:42.562',
        ];

        return $payment;
    }

    public function getFutureInvoice(string $subscriptionId, string $customerId, string $from, string $until, int $limit = null, bool $fetchAll = false)
    {
        $futureInvoice = [
          'data' => [
            [
              'subscriptionId' => $subscriptionId,
              'date' => '2019-07-01',
              'cycleStartDate' => '2019-07-01',
              'cycleEndDate' => '2019-07-31',
              'items' => [
                [
                  'description' => 'Share Link Bronze',
                  'amount' => [
                    'currency' => 'AUD',
                    'value' => 1.06,
                  ],
                  'tax' => [
                    'rate' => 10.0,
                  ],
                  'type' => 'subscription_payment',
                  'accountingCode' => 'SLB',
                ],
              ],
              'amount' => [
                'currency' => 'AUD',
                'value' => 200,
                'type' => null,
              ],
              'totalTax' => [
                'currency' => 'AUD',
                'value' => 0,
                'type' => null,
              ],
            ],
          ],
          'paging' => [
            'nextUrl' => null,
            'nextCursor' => 0,
            'limit' => 0,
            'totalCount' => 2,
          ],
        ];

        return $futureInvoice;
    }

    public function getSharedFutureInvoice()
    {
        $subscription = $this->getSubscription($this->faker->uuid);
        $startDate = Carbon::now()->addDays(30)->toDateString();
        $endDate = Carbon::now()->addDays(60)->toDateString();
        $futureInvoices = $this->getFutureInvoice(
            $subscription['id'],
            $subscription['customerId'],
            $startDate,
            $endDate,
            true
      );

        return $futureInvoices;
    }

    public function deleteFutureInvoice()
    {
        return [
          'entityId' => $this->faker->uuid,
          'delete' => 'true',
        ];
    }

    public function updateFutureInvoice(string $subscriptionId, string $cycleStartDate, string $date, array $items = [])
    {
        return [
          'data' => [
            [
              'subscriptionId' => $subscriptionId,
              'date' => '2019-07-01',
              'cycleStartDate' => $cycleStartDate,
              'cycleEndDate' => '2019-07-31',
              'items' => [
                [
                  'description' => 'Share Link Bronze',
                  'amount' => [
                    'currency' => 'AUD',
                    'value' => 1.06,
                  ],
                  'tax' => [
                    'rate' => 10.0,
                  ],
                  'type' => 'subscription_payment',
                  'accountingCode' => 'SLB',
                ],
              ],
              'amount' => [
                'currency' => 'AUD',
                'value' => 200,
                'type' => null,
              ],
              'totalTax' => [
                'currency' => 'AUD',
                'value' => 0,
                'type' => null,
              ],
            ],
          ],
          'paging' => [
            'nextUrl' => null,
            'nextCursor' => 0,
            'limit' => 0,
            'totalCount' => 2,
          ],
        ];
    }

    public function createFutureInvoice(string $subscriptionId, string $cycleStartDate, string $paymentMethodType)
    {
        $futureInvoice = [
          'id' => $this->faker->uuid,
          'documentNumber' => $this->faker->word,
          'date' => '2019-07-12',
          'dueDate' => '2019-09-01',
          'scheduledPaymentDate' => null,
          'status' => 'PAID',
          'memo' => '',
          'items' => [
            [
              'description' => 'Share Link Bronze',
              'amount' => [
                'currency' => 'AUD',
                'value' => 50,
                'type' => null,
              ],
              'tax' => [
                'rate' => 10,
              ],
              'id' => $this->faker->uuid,
              'type' => 'subscription_payment',
              'discounted' => [
                'currency' => 'AUD',
                'value' => 0,
                'type' => null,
              ],
              'accountingCode' => 'SLB',
            ],
          ],
          'amount' => [
            'currency' => 'AUD',
            'value' => $this->faker->randomNumber(2),
            'type' => null,
          ],
          'amountWithoutDiscount' => [
            'currency' => 'AUD',
            'value' => $this->faker->randomNumber(2),
            'type' => null,
          ],
          'totalDiscounted' => [
            'currency' => 'AUD',
            'value' => $this->faker->randomNumber(2),
            'type' => null,
          ],
          'totalRefunded' => [
            'currency' => 'AUD',
            'value' => $this->faker->randomNumber(2),
            'type' => null,
          ],
          'totalTax' => [
            'currency' => 'AUD',
            'value' => $this->faker->randomNumber(2),
            'type' => null,
          ],
          'customerId' => 'afed8838-a6ea-4ae0-b7dd-a092b8d7a13b',
          'subscriptionId' => $subscriptionId,
          'subscriptionName' => 'Share Link Bronze',
          'paymentMethodToken' => $paymentMethodType,
          'autoPayment' => false,
          'createdOn' => '2019-07-12T10:49:14.122',
        ];

        return $futureInvoice;
    }

    public function refundInvoice(string $invoiceId, string $amountCurrency, int $amountValue, array $items = [])
    {
        $invoices = [
          'id' => $invoiceId,
          'documentNumber' => $this->faker->word,
          'date' => '2019-07-12',
          'dueDate' => '2019-09-01',
          'scheduledPaymentDate' => null,
          'status' => 'PAID',
          'memo' => '',
          'items' => [
            [
              'description' => 'Share Link Bronze',
              'amount' => [
                'currency' => $amountCurrency,
                'value' => 50,
                'type' => null,
              ],
              'tax' => [
                'rate' => 10,
              ],
              'id' => $this->faker->uuid,
              'type' => 'subscription_payment',
              'discounted' => [
                'currency' => $amountCurrency,
                'value' => 0,
                'type' => null,
              ],
              'accountingCode' => 'SLB',
            ],
          ],
          'amount' => [
            'currency' => $amountCurrency,
            'value' => $amountValue,
            'type' => null,
          ],
          'amountWithoutDiscount' => [
            'currency' => $amountCurrency,
            'value' => $this->faker->randomNumber(2),
            'type' => null,
          ],
          'totalDiscounted' => [
            'currency' => $amountCurrency,
            'value' => $this->faker->randomNumber(2),
            'type' => null,
          ],
          'totalRefunded' => [
            'currency' => $amountCurrency,
            'value' => $this->faker->randomNumber(2),
            'type' => null,
          ],
          'totalTax' => [
            'currency' => $amountCurrency,
            'value' => $this->faker->randomNumber(2),
            'type' => null,
          ],
          'customerId' => $this->faker->uuid,
          'subscriptionId' => $this->faker->uuid,
          'subscriptionName' => 'Share Link Bronze',
          'paymentMethodToken' => $this->faker->uuid,
          'autoPayment' => false,
          'createdOn' => '2019-07-12T10:49:14.122',
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
          'data' => [
            [
              'id' => $this->faker->uuid,
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
                'postalCode' => $this->faker->randomNumber(4, true),
                'state' => $this->faker->state,
                'countryCode' => $this->faker->countryCode,
                'city' => $this->faker->city,
              ],
              'metadata' => [
                'identifierType' => $this->faker->word,
                'identifierID' => $this->faker->randomNumber(3),
              ],
            ],
          ],
          'paging' => [
            'nextUrl' => null,
            'nextCursor' => 0,
            'limit' => 0,
            'totalCount' => 1,
          ],
        ];

        return $customers;
    }

    public function getCustomer(string $customerId)
    {
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
            'postalCode' => $this->faker->randomNumber(4, true),
            'state' => $this->faker->state,
            'countryCode' => $this->faker->countryCode,
            'city' => $this->faker->city,
          ],
          'metadata' => [
            'identifierType' => $this->faker->word,
            'identifierID' => $this->faker->randomNumber(3),
          ],
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
            'address1' => $this->faker->streetAddress,
            'address2' => $this->faker->buildingNumber,
            'postalCode' => $this->faker->randomNumber(4, true),
            'state' => $this->faker->state,
            'countryCode' => $this->faker->countryCode,
            'city' => $this->faker->city,
          ],
          'metadata' => [
            'identifierType' => $this->faker->word,
            'identifierID' => $this->faker->randomNumber(3),
          ],
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
        $notes = [
          'data' => [
            [
              'id' => $this->faker->uuid,
              'invoiceId' => $this->faker->uuid,
              'documentNumber' => 'CN0000000000000001',
              'date' => '2019-04-01',
              'status' => 'PAID',
              'items' => [],
              'amount' => [],
              'totalTax' => [],
              'reason' => 'REFUND',
              'customer_id' => $customerId,
              'paymentMethodToken' => $this->faker->uuid,
            ],
          ],
          'paging' => [
            'nextUrl' => null,
            'nextCursor' => 0,
            'limit' => 0,
            'totalCount' => 0,
          ],
        ];

        return $notes;
    }

    public function getCreditNote(string $creditNoteId)
    {
        $note = [
          'id' => $creditNoteId,
          'invoiceId' => $this->faker->uuid,
          'documentNumber' => 'CN0000000000000001',
          'date' => '2019-04-01',
          'status' => 'PAID',
          'items' => [],
          'amount' => [],
          'totalTax' => [],
          'reason' => 'REFUND',
          'customerId' => $this->faker->uuid,
          'paymentMethodToken' => $this->faker->uuid,
        ];

        return $note;
    }

    private function requestToken(string $refreshToken = null)
    {
        $tokenObj = [
          'refresh_token' => $this->faker->uuid,
          'access_token' => $this->faker->uuid,
        ];

        $tokenObj['expiration'] = Carbon::now()->addSeconds(3590); // Just under hour

        return $tokenObj;
    }

    private function getAccessToken()
    {
        return $this->faker->uuid;
    }

    public function getWebhooks(int $limit = null, int $cursor = null)
    {
        return [
        'resultCount' => 1,
        'totalCount' => 1,
        'data' => [
          [
            'url' => 'http://api.sample.test',
            'eventTypes' => [
              'customer_create',
            ],
            'id' => $this->faker->uuid,
            'createdOn' => '2019-07-12T08:59:21.036',
          ],
          [
            'url' => 'http://api.sample2.test',
            'eventTypes' => [
              'customer_create',
            ],
            'id' => $this->faker->uuid,
            'createdOn' => '2019-07-12T08:59:21.036',
          ],
        ],
      ];
    }

    public function createWebhook(string $url, array $eventTypes)
    {
        return [
        'id' => '31027aca-9b31-4462-a34e-42f70eceb243',
        'createdOn' => '2019-07-12T08:59:21.036',
        'url' => $url,
        'eventTypes' => $eventTypes,
      ];
    }

    public function getWebhookNotificationLogs()
    {
        return [
        'resultCount' => 0,
        'totalCount' => 0,
        'data' => [],
      ];
    }

    public function simulateWebHook(string $eventType)
    {
        return [
        'event' => $eventType,
        'callbackUrls' => [
          'http://api.sample.test',
        ],
      ];
    }

    public function getWebhookDetails(string $webhookId)
    {
        return [
        'id' => $webhookId,
        'createdOn' => '2019-07-12T08:59:21.036',
        'url' => 'http://api.sample.test',
        'eventTypes' => [
          'customer_create',
        ],
      ];
    }

    public function updateWebhook(string $webhookId, string $url = null, array $eventTypes = [], string $bodyWebhookId = null, bool $updateSecurity = true)
    {
        return [
        'id' => $webhookId,
        'createdOn' => '2019-07-12T09:11:24.047',
        'url' => 'http://api.sample.test',
        'eventTypes' => $eventTypes,
      ];
    }

    public function deleteWebhook(string $webhookId)
    {
        return [
        'entityId' => $webhookId,
        'deleted' => true,
      ];
    }

    public function resendEvent(string $eventId)
    {
        return [];
    }
}
