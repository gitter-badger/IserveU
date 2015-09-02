<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;

use App\Figure;
use App\Motion;
use Auth;

class MotionFigureController extends ApiController {


    public function index($motion){
    	return $motion->figures;
	}



	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create($motion){
		if(!Auth::user()->can('create-motions')){
			abort(401,'You do not have permission to create a motion');
		}

		return (new Figure)->fields;		
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store($motion)
	{
		if(!Auth::user()->can('create-motions')){
			abort(401,'You do not have permission to create a motion');
		}

		$validator = Validator::make(array('file' => Input::file('file')),array('file'=>'image'));
        if ($validator->fails()){
            abort(403,'file is not an image');
        }


		try {
      		      		$img = Image::make(Request::file('file'))->resize(1920, null, function($constraint){
      			$constraint->aspectRatio();
      			$constraint->upsize();
      		});
		} catch (Exception $e) {
		    abort(400,'There was an error uploading and resizing the image');
		} catch (NotReadableException $e){
			abort(403,"Unable to read image from file");
		}

        $file = md5($img->response()).".png";
        //$directory .="/database/seeds/thefile.csv";
        $img->save(getcwd()."/uploads/figures/$file");

        $input = Request::all();
        $input['file'] = $file;
        $input['motion_id'] = $motion->id;

		$figure = (new Figure)->secureFill($input);

		if(!$figure->save()){
		 	abort(403,$figure->errors);
		}
     	
     	return $figure;
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Motion $motion, Figure $figure)
	{	
		return $figure;
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit(Motion $motion,Figure $figure)
	{
		if(!Auth::user()->can('create-motions')){
			abort(403,'You do not have permission to create/update motions');
		}

		if(!$motion->user_id!=Auth::user()->id && !Auth::user()->can('administrate-motions')){ //Is not the user who made it, or the site admin
			abort(401,"This user can not edit motion ($id)");
		}

		return $figure->fields;
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Motion $motion,Figure $figure)
	{
		if(!Auth::user()->can('create-motions')){
			abort(403,'You do not have permission to update a motion');
		}

		if(!$motion->user_id!=Auth::user()->id && !Auth::user()->can('administrate-motions')){ //Is not the user who made it, or the site admin
			abort(401,"This user can not edit motion ($id)");
		}

		try {
      		$img = Image::make(Request::file('file'))->resize(1920, null, function($constraint){
      			$constraint->aspectRatio();
      			$constraint->upsize();
      		});
		} catch (Exception $e) {
		    abort(400,'There was an error uploading and resizing the image');
		} catch (NotReadableException $e){
			abort(403,"Unable to read image from file");
		}

        $file = md5($img->response()).".png";
        //$directory .="/database/seeds/thefile.csv";
        $img->save(getcwd()."/uploads/figures/$file");

        $input = Request::all();
        $input['file'] = $file;
        $input['motion_id'] = $motion->id;

		$figure->secureFill($input);

		if(!$figure->save()){
		 	abort(403,$figure->errors);
		}
     	
     	return $figure;
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Motion $motion, Figure $figure)
	{
		if(Auth::user()->id != $motion->user_id && !Auth::user()->can('delete-motions')){
			abort(401,"You do not have permission to delete this motion");
		}

		$figure->delete();
		return $figure;
	}


}