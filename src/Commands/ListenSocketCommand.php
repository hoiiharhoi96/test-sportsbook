<?php

namespace App\Commands;

use App\Interfaces\ImportInterface;
use App\Interfaces\ParserInterface;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use React\EventLoop\Loop;
use Ratchet\Client\Connector;
use Ratchet\Client\WebSocket;
use Ratchet\RFC6455\Messaging\MessageInterface;

#[AsCommand(
    name: 'socket:listen',
)]
class ListenSocketCommand extends Command
{
    public function __construct(private readonly ParserInterface $parser, private readonly ImportInterface $importer)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>Start event-loop</info>');

        $loop = Loop::get();

        new Connector($loop)($_ENV['SPORT_KRALBET_WEBSOKET_URL'])->then(
            function (WebSocket $conn) use ($output) {
                $output->writeln('<info>Successfully connected! We are sending the subscription....</info>');

                $conn->send('42["subscribe-PreliveEvents",{"market_group":"prelive","locale":"en_US"}]');
                $output->writeln('<info>The subscription is completed. We are waiting for the data...</info>');

                $conn->on('message', function (MessageInterface $msg, WebSocket $conn) use ($output) {

                    $message = $msg->getPayload();

                    match (true) {
                        '2' === $message  => $conn->send('3'),
                        str_starts_with($message, '42') => $this->import(substr($message, 2), $output),
                        default => null
                    };
                });
            },
            function (Exception $e) use ($output, $loop) {
                $output->writeln(sprintf('<error>Could not connect: %s</error>', $e->getMessage()));
                $loop->stop();
            }
        );

        $loop->run();

        return Command::SUCCESS;
    }

    private function import(string $message, OutputInterface $output): void
    {
         $data = json_decode($message, true)[1] ?? [];
         if ([] === $data) {
             $output->writeln('<comment>' . __FUNCTION__ . ': The data is empty...</comment>');

             return;
         }

         $start = microtime(true);

         $this->importer->import($this->parser->parse($data));

         round((microtime(true) - $start), 3)
             |> (fn($i): string => sprintf('<info>Import: Successful: %3f sec...</info>', $i))
             |> $output->writeln(...);
    }
}
