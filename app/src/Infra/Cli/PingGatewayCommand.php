<?php

declare(strict_types=1);

namespace App\Infra\Cli;

use App\Domain\Order\Service\PaymentGatewayClientInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class PingGatewayCommand extends Command
{
    public function __construct(
        private readonly PaymentGatewayClientInterface $gatewayClient
    ) {
        parent::__construct('payment-gateway:ping');
    }

    public function getDescription(): string
    {
        return "Pings the payment gateway";
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $statusCode = $this->gatewayClient->ping();
        $output->writeln("Status code: {$statusCode}");
        return self::SUCCESS;
    }
}
