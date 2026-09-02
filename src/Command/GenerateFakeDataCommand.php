<?php

namespace App\Command;

use App\Entity\Customer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\FakeData\CustomerGenerator;
use App\Service\FakeData\OrderGenerator;
use App\Service\FakeData\LoyaltyTriggerGenerator;

#[AsCommand(
    name: 'app:generate-fake-data',
    description: 'Create customers and orders',
)]
class GenerateFakeDataCommand extends Command
{   
    private EntityManagerInterface $em;
    private CustomerGenerator $customerGenerator;
    private OrderGenerator $orderGenerator;
    private LoyaltyTriggerGenerator $loyaltyTriggerGenerator;

    public function __construct(
        EntityManagerInterface $em,
        CustomerGenerator $customerGenerator,
        OrderGenerator $orderGenerator,
        LoyaltyTriggerGenerator $loyaltyTriggerGenerator
    ) {
        parent::__construct();
        $this->em = $em;
        $this->customerGenerator = $customerGenerator;
        $this->orderGenerator = $orderGenerator;
        $this->loyaltyTriggerGenerator = $loyaltyTriggerGenerator;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        for ($i = 1; $i <= 200; $i++) {
            $customer = $this->customerGenerator->createCustomer();

            $this->em->persist($customer);

            if ($i % 50 === 0) {
                $this->em->flush();
                $this->em->clear();
            }
        }

        $this->em->flush();

        $allCustomers = $this->em->getRepository(Customer::class)->findAll();

        for ($i = 1; $i <= 500; $i++) {
            $customer = $allCustomers[array_rand($allCustomers)];
            $order = $this->orderGenerator->createOrder($customer);

            $this->em->persist($order);

            if ($i % 2 === 0 ) {
                $loyaltyTrigger = $this->loyaltyTriggerGenerator->createLoyaltyTrigger($order);
                $this->em->persist($loyaltyTrigger);
            }

            if ($i % 50 === 0) {
                $this->em->flush();
            }
        }
        
        $this->em->flush();
        $this->em->clear();

        return Command::SUCCESS;
    }
}
