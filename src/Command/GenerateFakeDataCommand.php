<?php

namespace App\Command;

use App\Entity\Customer;
use App\Entity\LoyaltyTrigger;
use App\Entity\Order;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;

#[AsCommand(
    name: 'app:generate-fake-data',
    description: 'Create customers and orders',
)]
class GenerateFakeDataCommand extends Command
{   

    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {   
        parent::__construct();
        $this->em = $em;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $faker = Factory::create('ru_RU');

        $countCustomers = 1;
        while ($countCustomers <= 200) {

            $customer = new Customer;
            
            $customerEmail = $faker->unique()->email;
            $customerPhone = $faker->unique()->phoneNumber;
            $customerFirstName = $faker->firstName('male');
            $customerLastName = $faker->lastName('male');

            $customer->setEmail($customerEmail);
            $customer->setPhone($customerPhone);
            $customer->setFirstName($customerFirstName);
            $customer->setLastName($customerLastName);

            $this->em->persist($customer);

            if ($countCustomers % 50 === 0) {
                $this->em->flush();
                $this->em->clear();
            }

            $countCustomers++;
        }

        $this->em->flush();

        $customerRepo = $this->em->getRepository(Customer::class);
        $allCustomers = $customerRepo->findAll();

        $countOrders = 1;
        $countLoyaltyTrigger = 1;

        while ($countOrders <= 500) {
            $customer = $allCustomers[array_rand($allCustomers)];

            $order = new Order;

            $orderStatus = $faker->randomElement(['complete', 'new', 'cancel-other']);
            $orderTotalAmount = (string) $faker->randomFloat(2, 1000, 5000);
            $orderCreatedAt = \DateTimeImmutable::createFromMutable(
                $faker->dateTimeBetween('-1 year', 'now')
            );

            $order->setCustomer($customer);
            $order->setStatus($orderStatus);
            $order->setTotalAmount($orderTotalAmount);
            $order->setCreatedAt($orderCreatedAt);

            $this->em->persist($order);

            if ($countOrders % 50 === 0) {
                $this->em->flush();
            }

            if ($countOrders %2 === 0) {
                $loyaltyTrigger = new LoyaltyTrigger;

                $loyaltyTrigger->setType('credit_for_order');
                $loyaltyTrigger->setCreatedAt($orderCreatedAt);
                $loyaltyTrigger->setPointsEarned($faker->numberBetween(100, 500));
                $loyaltyTrigger->setRelatedOrder($order);

                $this->em->persist($loyaltyTrigger);

                if($countLoyaltyTrigger % 50 === 0) {
                    $this->em->flush();
                }

                $countLoyaltyTrigger++;
            }

            $countOrders++;
        }

        $this->em->flush();
        $this->em->clear();

        return Command::SUCCESS;
    }
}
