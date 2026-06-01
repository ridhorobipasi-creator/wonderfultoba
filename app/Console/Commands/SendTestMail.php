<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendTestMail extends Command
{
    protected $signature = 'mail:test {to? : Recipient email (optional)}';

    protected $description = 'Send a test email using current mailer (useful to verify log/mail driver)';

    public function handle()
    {
        $to = $this->argument('to') ?? config('mail.from.address', 'test@example.com');

        try {
            Mail::raw('This is a test email from the application (mail:test).', function ($m) use ($to) {
                $m->to($to)->subject('Test Email from App');
            });

            $this->info("Test email dispatched to: {$to} (check logs or configured mail transport)");

            return 0;
        } catch (\Exception $e) {
            $this->error('Failed to send test email: '.$e->getMessage());

            return 1;
        }
    }
}
