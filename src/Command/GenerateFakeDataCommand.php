<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputOption;
use App\Service\FakeData\CustomerGenerator;
use App\Service\FakeData\OrderGenerator;
use App\Service\RetailCrmClient;


#[AsCommand(
    name: 'app:generate-fake-data',
    description: 'Create customers and orders',
)]

class GenerateFakeDataCommand extends Command
{   
    private const PROGRESS_FORMAT = ' %current%/%max% [%bar%] %percent:3s%% %elapsed:6s%/%estimated:-6s% %memory:6s%';

    private CustomerGenerator $customerGenerator;
    private OrderGenerator $orderGenerator;
    private RetailCrmClient $retailCrmClient;

    public function __construct(
        CustomerGenerator $customerGenerator,
        OrderGenerator $orderGenerator,
        RetailCrmClient $retailCrmClient
    ) {
        parent::__construct();
        $this->customerGenerator = $customerGenerator;
        $this->orderGenerator = $orderGenerator;
        $this->retailCrmClient = $retailCrmClient;
    }

    protected function configure(): void
    {
        $this
            ->addOption(
                'quantity-generations',
                null,
                InputOption::VALUE_REQUIRED,
                'Количество генераций',
                10
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {   
        $quantityGenerations = (int) $input->getOption('quantity-generations');
        $output->writeln('Создание клиентов:');
        $customerProgress = new ProgressBar($output, $quantityGenerations);
        $customerProgress->setFormat(self::PROGRESS_FORMAT);
        $customerProgress->start();

        $customers = [];

        for ($i = 1; $i <= $quantityGenerations; $i++) {
            $customer = $this->customerGenerator->createCustomer();
            $crmId = $this->retailCrmClient->createCustomer($customer);

            if ($crmId !== null) {
                $customers[] = ['crmId' => $crmId, 'data' => $customer];
            }
            $customerProgress->advance();
        }
        $customerProgress->finish();
        $output->writeln('');

        if (empty($customers)) {
            $output->writeln('<error>No customers were created</error>');
            return Command::FAILURE;
        }

        $output->writeln('Создание заказов:');
        $orderProgress = new ProgressBar($output, count($customers));
        $orderProgress->setFormat(self::PROGRESS_FORMAT);
        $orderProgress->start();

        foreach ($customers as $entry) {
            $orderData = $this->orderGenerator->createOrder($entry['crmId'], $entry['data']);
            $orderCrmId = $this->retailCrmClient->createOrder($orderData);

            $orderProgress->advance();
        }
        $orderProgress->finish();
        $output->writeln('');

        return Command::SUCCESS;
    }
}
