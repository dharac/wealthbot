<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Deposit;
use App\CronJob;

class CustomCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'custom:command';

    /**
     * The console command description.
     *
     * @var string
     */ 
    protected $description = 'Payout for Redeposit, Level Commission and Interest Payments';

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
     *
     * @return mixed
     */
    public function handle()
    {
        $status = Deposit::payoutOnDeposit();
        CronJob::InsertCronJob('INTEREST,COMMISSION CRON JOB');
        $this->info('Success !');
    }
}
