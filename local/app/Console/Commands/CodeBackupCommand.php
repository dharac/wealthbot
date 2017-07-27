<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\CodeBackup;
use App\Setting;
use App\CronJob;

class CodeBackupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'codebackup:command';

    /**
     * The console command description.
     *
     * @var string
     */ 
    protected $description = 'Weekly Code Backup';

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
        $backup_code = Setting::getData('backup_code');
        if($backup_code == 1)
        {
            $status = CodeBackup::getBackupCode();
            CronJob::InsertCronJob('CODE BACKUP');
            $this->info('Success!');
        }
    }
}
