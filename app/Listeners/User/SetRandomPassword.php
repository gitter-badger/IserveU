<?php

namespace App\Listeners\User;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SetRandomPassword
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle($event)
    {
        $user = $event->user;

        $randomPassword = str_random(50);

        $user->password = $randomPassword;

        if(!$user->save()){ //Validation failed show errors
            abort(403,$event->user->errors);
        }    

    }
}
