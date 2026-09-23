    <?php
    namespace App\Observers;
    use App\Models\{CustomerPoints};
    use Swaggest\JsonDiff\JsonDiff;
    class UserObserver
    {
        /**
         * Handle the UserDetail "created" event.
         *
         * @param  \App\Models\User  $user
         * @return void
         */
        public function created(CustomerPoints $customerPoints)
        {
            
            dd('ok');
            //VersionSystem::create($user);
        }
        
        /**
         * Handle updating events
         * Checks the destination result on the record, checks whether this has changed
         * If so, creates a new history for the event
         *
         * @param  User  $user
         * @return bool
         */
        public function updating(User $user): bool
        {
        }
        /**
         * Handle the User "updated" event.
         *
         * @param  \App\Models\UserDetail  $user
         * @return void
         */
        public function updated(User $user)
        {
           
        }
        /**
         * Handle the User "deleted" event.
         *
         * @param  \App\Models\UserDetail  $user
         * @return void
         */
        public function deleted(User $user)
        {
            //
        }
        /**
         * Handle the User "restored" event.
         *
         * @param  \App\Models\UserDetail  $user
         * @return void
         */
        public function restored(User $user)
        {
            //
        }
        /**
         * Handle the User "force deleted" event.
         *
         * @param  \App\Models\UserDetail  $user
         * @return void
         */
        public function forceDeleted(User $user)
        {
            //
        }
    }