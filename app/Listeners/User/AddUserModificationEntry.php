<?php

namespace App\Listeners\User;

use App\Events\User\UserUpdated;
use App\UserModification;
use JWTAuth;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AddUserModificationEntry
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
     * @param  UserUpdated  $event
     * @return void
     */
    public function handle($event)
    {
        $user = $event->user;
        $modifiedRecord = new UserModification;
        $token = JWTAuth::getToken();
        if($token){
            $creator = JWTAuth::parseToken()->authenticate();
            $modifiedRecord->modification_by_id = $creator->id;
        } else {
            $modifiedRecord->modification_by_id = $user->id; //Created own record
        }
     
        $modifiedRecord->modification_to_id = $user->id;
        $modifiedRecord->fields = json_encode($user->getDirty());
        $modifiedRecord->save();
    }
}
