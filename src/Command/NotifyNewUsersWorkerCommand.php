<?php

namespace App\Command;

use App\Service\RedisCacheManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Mailer\MailerInterface;

#[AsCommand(
    name: 'app:notify-new-users-worker',
    description: 'Worker command to process new user notifications from Redis queue.',
)]
class NotifyNewUsersWorkerCommand extends Command
{
    public function __construct(
        private readonly RedisCacheManager $redisCacheManager,
        private readonly MailerInterface $mailer
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $message = $this->redisCacheManager->dequeue('new_users_notifications');

        while ($message) {
            // Process the message (e.g., send an email notification)
            $output->writeln('Processing message: ' . $message);

            $emailSend = (new \Symfony\Component\Mime\Email())
                ->from('david.domenech@urv.cat')
                ->to('david.domenech@urv.cat')
                ->subject('New User Notification')
                ->text('A new user has registered.');

            try {
                $this->mailer->send($emailSend);
                $output->writeln('Email sent successfully.');
            } catch (\Throwable $e) {
                $output->writeln('Failed to send email: ' . $e->getMessage());
            }
            
            // Dequeue the next message
            $message = $this->redisCacheManager->dequeue('new_users_notifications');
        }

        $output->writeln('No more messages to process.');

        return Command::SUCCESS;
    }
}