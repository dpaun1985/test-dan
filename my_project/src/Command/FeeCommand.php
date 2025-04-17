<?php
namespace App\Command;

use App\Service\FeeService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:calculate-fee')]
class FeeCommand extends Command
{
    /**
     * Command constructor.
     *
     * @param FeeService $feeService
     */
    public function __construct(private readonly FeeService $feeService)
    {
        parent::__construct();
    }

    /**
     * Configure the command options and arguments.
     *
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Commission fee calculation')
            ->setHelp('Calculates the commision fee for deposits and withdrawals')
            ->addArgument('file', InputArgument::REQUIRED, 'Operations file CSV');
        ;
    }

    /**
     * Execute the command.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln([
            'Fee Calculator',
            '============',
            '',
        ]);
        $output->writeln('Loading file: ' . $input->getArgument('file') . '...');
        $file = $input->getArgument('file');

        if (!file_exists($file)) {
            $output->writeln('File not found: ' . $file);
            return Command::FAILURE;
        }
        if (($handle = fopen($file, 'r')) === false) {
            $output->writeln('File not found: ' . $file);
            return Command::FAILURE;
        }
        $output->writeln('Processing file...');
        while (($client = fgetcsv($handle, 1000, ',', '"', '\\')) !== false) {
            if (empty($client)) {
                continue; // Skip empty rows
            }
            // Process the row
            try {
                $fee = $this->feeService->calculateFee($client);
            } catch (\Exception $e) {
                $output->writeln('Error processing row: ' . implode(',', $client) . ' - ' . $e->getMessage());
                continue; // Skip to the next row on error
            }

            $output->writeln($fee);
        }
        fclose($handle);

        $output->writeln('File processed successfully.');
        return Command::SUCCESS;
    }
}