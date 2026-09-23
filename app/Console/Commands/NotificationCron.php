<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Notification;
use App\User;
use App\PushNotification;
class NotificationCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Nofification:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        // Capped at 50 so one run's work stays bounded even after a burst of
        // notifications - the next minute's run picks up whatever is left,
        // instead of a single row draining the whole queue one per minute.
        $list = Notification::where('status', 'Pending')->orderBy('id', 'asc')->limit(50)->get();

        foreach ($list as $row) {
            if ($row->user_id == 0) {
                $data = User::join('role_users', 'role_users.user_id', '=', 'users.id')
                    ->join('roles', 'roles.id', '=', 'role_users.role_id')
                    ->where('roles.slug', '=', $row->type)
                    ->where('DeviceToken', '!=', '0')
                    ->pluck('DeviceToken')->toArray();
            } else {
                $data = User::join('role_users', 'role_users.user_id', '=', 'users.id')
                    ->join('roles', 'roles.id', '=', 'role_users.role_id')
                    ->where('DeviceToken', '!=', '0')
                    ->where('users.id', $row->user_id)
                    ->pluck('DeviceToken')->toArray();
            }

            PushNotification::send($row, $data, $row->target_screen);
            Notification::where('id', $row->id)->update(['status' => 'Sent']);
        }

        $this->info('Nofification:Cron Cummand Run successfully! ('.count($list).' sent)');
    }
}
