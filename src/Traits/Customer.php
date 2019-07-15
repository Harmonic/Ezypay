<?php

namespace harmonic\Ezypay\Traits;

trait Customer
{
    /**
     * Get credit notes.
     *
     * @param bool $fetchAll
     * @param string $name
     * @param string $firstName
     * @param string $lastName
     * @param string $companyName
     * @param string $referenceCode
     * @param string $customerNumber
     * @param string $createdDate
     * @param string $sortExpression
     * @param int $limit
     * @param int $cursor
     * @return array
     */
    public function getCustomers(bool $fetchAll = false, string $name = null, string $firstName = null, string $lastName = null, string $companyName = null, string $referenceCode = null, string $customerNumber = null, string $createdDate = null, string $sortExpression = null, int $limit = null, int $cursor = null)
    {
        $filters = [
            'name' => $name,
            'firstName' => $firstName,
            'lastName' => $lastName,
            'companyName' => $companyName,
            'referenceCode' => $referenceCode,
            'customerNumber' => $customerNumber,
            'createdDate' => $createdDate,
            'sortExpression' => $sortExpression,
            'limit' => $limit,
            'cursor' => $cursor,
        ];

        return $this->paginate('billing/customers', $filters, $fetchAll);
    }

    /**
     * Create a new customer.
     *
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param string $address1
     * @param string $address2
     * @param int $postCode
     * @param string $city
     * @param string $state
     * @param string $country
     * @param string $companyName
     * @param string $identifierType
     * @param int $identifierID
     * @return object Customer
     */
    public function createCustomer(string $firstName, string $lastName, string $email, string $address1, string $address2, string $postCode, string $city, string $state, string $country, string $companyName, string $identifierType = '', int $identifierID = null)
    {
        $data = [
            'firstName' => $firstName,
            'lastName' => $lastName,
            'email' => $email,
            'address' => [
                'address1' => $address1,
                'address2' => $address2,
                'postalCode' => $postCode,
                'city' => $city,
                'state' => $state,
                'countryCode' => strtoupper($country),
            ],
            'companyName' => $companyName,
        ];

        if (! empty($identifierType) !== null && $identifierID !== null) {
            $data['metadata'] = [
                'identifierType' => $identifierType,
                'identifierID' => $identifierID,
            ];
        }

        $customer = $this->request('POST', 'billing/customers/', $data);

        return $customer;
    }

    /**
     * Get a specific Customer.
     *
     * @param string $customerId
     * @return object Customer
     */
    public function getCustomer(string $customerId)
    {
        $response = $this->request('GET', 'billing/customers/'.$customerId);

        return \harmonic\Ezypay\Resources\Customer::make($response)->resolve();
    }

    /**
     * Update customer.
     *
     * @param string $customerId
     * @param string $email
     * @param string $firstName
     * @param string $lastName
     * @param string $address1
     * @param string $gender
     * @param string $homePhone
     * @param string $mobilePhone
     * @param string $referenceCode
     * @param string $companyName
     * @param string $dateOfBirth
     * @param string $address2
     * @param string $postalCode
     * @param string $city
     * @param string $state
     * @param string $countryCode
     * @return object Customer
     */
    public function updateCustomer(string $customerId, string $email, string $firstName, string $lastName, string $address1, string $companyName = null, string $gender = null, string $homePhone = null, string $mobilePhone = null, string $referenceCode = null, string $dateOfBirth = null, string $address2 = null, string $postCode = null, string $city = null, string $state = null, string $countryCode = null)
    {
        $data = [
            'firstName' => $firstName,
            'lastName' => $lastName,
            'email' => $email,
            'gender' => $gender,
            'homePhone' => $homePhone,
            'mobilePhone' => $mobilePhone,
            'referenceCode' => $referenceCode,
            'dateOfBirth' => $dateOfBirth,
            'address' => [
                'address1' => $address1,
                'address2' => $address2,
                'postalCode' => $postCode,
                'city' => $city,
                'state' => $state,
                'countryCode' => strtoupper($countryCode),
            ],
            'companyName' => $companyName,
        ];

        $customer = $this->request('PUT', 'billing/customers/'.$customerId, $data);

        return $customer;
    }
}
