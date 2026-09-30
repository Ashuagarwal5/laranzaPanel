<?php

namespace App\Console\Commands;

use App\Services\FcmV1;
use App\User;
use Illuminate\Console\Command;

class PushTest extends Command
{
    protected $signature = 'push:test {user_id}';

    protected $description = 'Send a test push to a user and print the raw FCM result, to diagnose why pushes are not arriving';

    public function handle()
    {
        $user = User::find($this->argument('user_id'));
        if (!$user) {
            $this->error('User not found.');
            return 1;
        }

        $token = $user->DeviceToken;
        $this->line('DeviceToken: '.(empty($token) ? '(empty)' : substr($token, 0, 25).'... ('.strlen($token).' chars)'));
        if (empty($token) || in_array($token, ['0', '1'], true)) {
            $this->error('This user has no real device token - the app has not registered one (permission denied, Expo Go, or not logged in on a new build).');
            return 1;
        }

        $result = FcmV1::send($token, 'Test push', 'If you see this, push works.', ['screen' => 'notifications']);
        $this->line('Status: '.$result['status']);
        $this->line($result['msg']);

        return $result['status'] === 'success' ? 0 : 1;
    }
}
