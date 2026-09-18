<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use App\Dto\CustomerData;
use RetailCrm\Api\Client;
use RetailCrm\Api\Model\Entity\Customers\Customer;
use RetailCrm\Api\Model\Entity\Customers\CustomerPhone;
use RetailCrm\Api\Model\Request\Customers\CustomersCreateRequest;
use RetailCrm\Api\Exception\Api\ApiErrorException;
use RetailCrm\Api\Model\Entity\Customers\CustomerAddress;
use RetailCrm\Api\Model\Entity\Orders\Order as OrdersOrder;
use RetailCrm\Api\Model\Request\Orders\OrdersCreateRequest;
use RetailCrm\Api\Model\Entity\Orders\Items\OrderProduct as ItemsOrderProduct;


class RetailCrmClient
{
    private Client $client;
    private LoggerInterface $logger;

    public function __construct(Client $client, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    public function createCustomer(CustomerData $data): ?int
    {
        $customer = new Customer();
        $customer->firstName = $data->firstName;
        $customer->lastName = $data->lastName;
        $customer->email = $data->email;
        $customer->birthday = $data->birthday;

        $phone = new CustomerPhone();
        $phone->number = $data->phone;
        $customer->phones = [$phone];

        $customerAddress = new CustomerAddress;
        $customerAddress->city = $data->address->city;
        $customerAddress->street = $data->address->street;
        $customerAddress->building = $data->address->building;
        $customerAddress->flat = $data->address->flat;

        $customer->address = $customerAddress;

        $request = new CustomersCreateRequest();
        $request->customer = $customer;
        $request->site = $data->site;

        try {
            $response = $this->client->customers->create($request);
            if ($response->success && $response->id !== null) {
                return $response->id;
            }
            $this->logger->warning('RetailCRM customer creation failed', $response->errors ?? []);
        } catch (ApiErrorException $exception) {
            $errorResponse = $exception->getErrorResponse();
            $this->logger->error('RetailCRM customer error', [
                'message' => $exception->getMessage(),
                'errors'  => $errorResponse->errors ?? null,
                'status'  => $exception->getStatusCode(),
            ]);
        } catch (\Throwable $exception) {
            $this->logger->error(sprintf('Неизвестная ошибка: %s', $exception->getMessage()));
        }
        return null;
    }

    public function createOrder(array $data): ?int
    {
        $order = new OrdersOrder();
        $customer = new Customer();

        $customer->id = $data['customerId'];
        $order->customer = $customer;
        $order->firstName = $data['firstName'];
        $order->lastName = $data['lastName'];
        $order->email = $data['email'];
        $order->phone = $data['phone'];
        $order->countryIso = $data['countryIso'];
        $order->orderType = $data['orderType'];
        $order->status = $data['status'];
        $order->orderMethod = $data['orderMethod'];
        $order->createdAt = new \DateTimeImmutable($data['createdAt']);

        $items = [];
        foreach ($data['items'] as $row) {
            $product = new ItemsOrderProduct();
            $product->productName  = $row['productName'];
            $product->quantity = $row['quantity'];
            $product->initialPrice = $row['price'];
            $items[] = $product;
        }
        $order->items = $items;

        $request = new OrdersCreateRequest();
        $request->order = $order;
        $request->site  = $data['site'];

        try {
            $response = $this->client->orders->create($request);
            if ($response->success && $response->id !== null) {
                return $response->id;
            }
            $this->logger->warning('RetailCRM order creation failed', $response->errors ?? []);
        } catch (ApiErrorException $exception) {
            $errorResponse = $exception->getErrorResponse();
            $this->logger->error('RetailCRM order error', [
                'message' => $exception->getMessage(),
                'errors'  => $errorResponse->errors ?? null,
                'status'  => $exception->getStatusCode(),
            ]);
        } catch (\Throwable $exception) {
            $this->logger->error(sprintf('Неизвестная ошибка: %s', $exception->getMessage()));
        }
        return null;
    }
}
