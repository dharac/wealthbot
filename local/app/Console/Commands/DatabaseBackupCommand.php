<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\DatabaseBackup;
use App\Setting;
use App\CronJob;

class DatabaseBackupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dbbackup:command';

    /**
     * The console command description.
     *
     * @var string
     */ 
    protected $description = 'Daily Database Backup';

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
        $backup_code = Setting::getData('backup_sql');
        if($backup_code == 1)
        {
            $status = DatabaseBackup::sendEmail();
            CronJob::InsertCronJob('SQL BACKUP');
            $this->info('Success!');
        }
    }
}
