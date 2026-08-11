<?php

namespace App\Command;

use App\Entity\Customer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;

#[AsCommand(
    name: 'app:generate-fake-data',
    description: 'Create customers and orders in RetailCRM',
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

        $startCount = 1;
        while ($startCount <= 10) {

            $customer = new Customer;
            
            $customerEmail = $faker->unique()->email;
            $customerPhone = $faker->unique()->phoneNumber;
            $customerFirstName = $faker->unique()->name('male');
            $customerLastName = $faker->unique()->lastName('male');

            $customer->setEmail($customerEmail);
            $customer->setPhone($customerPhone);
            $customer->setFirstName($customerFirstName);
            $customer->setLastName($customerLastName);

            $this->em->persist($customer);
            $this->em->flush($customer);

        }

        return Command::SUCCESS;
    }
}
