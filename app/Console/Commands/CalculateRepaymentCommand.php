<?php

namespace App\Console\Commands;

use App\Events\Loan\LoanDueChecked;
use Illuminate\Console\Command;

class CalculateRepaymentCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calculate:repayments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate interest and principal due at a particular time, for selected loans.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        event(new LoanDueChecked());
    }
}
