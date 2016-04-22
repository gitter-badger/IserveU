<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Vote;
use Auth;

class ShowMotionRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $motion =  $this->route()->parameter('motion');

        // dd($motion->typedSections);

        if($motion->status < 2){
            if(!Auth::user() || !Auth::user()->can('administrate-motions')){
                return false;
            }
        }

        if(Auth::check()){
            Vote::where('motion_id',$motion->id)->where('user_id',Auth::user()->id)->update(['visited'=>true]);
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}