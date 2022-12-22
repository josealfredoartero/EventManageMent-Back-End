<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use App\Models\Image;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\CommentPublicationController;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PublicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Query for all publications
        $publications = Publication::select("publications.*", "users.name")->join("users", "users.id", "=", "publications.id_user")->orderBy("created_at",'DESC')->get();

        //Touring publications array for inserting images by publication
        foreach($publications as $publication){
            //Query for all image where "id_publication"
            $publication->images = Image::select()->where('id_publication',$publication->id)->get();
        }

        //Returning publication with their images
        return response()->json($publications,Response::HTTP_OK);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validating data
        $request->validate([
            'title' => 'required|min:3|max:50',
            'description' => 'required',
            'images' => 'required'
        ]);

        //Getting user data by token
        $user = auth()->user();
        //Validating if user is admin
        if($user->id_role = 1){
            $publication = new Publication;
            //Inserting data
            $publication->title = $request->title;
            $publication->description = $request->description;
            $publication->id_user = $user->id;
            //Saving publication
            $publication->save();

            $image = new ImageController();
            //Saving all images in the storage with id_publication
            $image->store($request->images, $publication->id);

            return response()->json(['message'=>'the Publication was saved successfully'], Response::HTTP_OK);

        }else{
            //Returning if user isn't admin
            return response()->json(['messaje'=>"unauthorized user"],Response::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Publication  $publication
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $publication = Publication::findOrFail($id);
        //Query for all image where "id_publication"
        $publication->images = Image::where('id_publication', $publication->id)->get();

        //Returning publication with their images
        return response()->json($publication, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Publication  $publication
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Publication $publication)
    {
        //Validating data
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);
        //Getting user data by token
        $user = auth()->user();
        //Validate if user is admin
        if($user->id_role === 1){
            //Inserting data 
            $publication->title = $request->title;
            $publication->description = $request->description;
    
            $image = new ImageController();
            //Validating if request bring images
            if($request->images){
                //Validating if request bring images to insert
                if($request->images["addImages"] !== []){
                    //Save storage with all images by id_publication
                    $image->store($request->images["addImages"],$publication->id);
                }
                //Validating if request brings images to delete
                if($request->images["deleteImages"] !== []){
                    //Touring all images to delete
                    foreach($request->images["deleteImages"] as $images){
                        //Delete images by id
                        $image->deleteImg($images["id"]);
                    }
                }
            }
            //Save publication
            $res = $publication->save();
            //Validate if publication has been saved correctly
            if($res){
                return response()->json(['message'=>"publication modified successfully"],Response::HTTP_OK);
            }else{
                return response()->json(['message'=>"publication not modified successfully"],Response::HTTP_ERROR);
            }
    
        }else{
            //Returning if user isn't admin
            return response()->json(['messaje'=>"unauthorized user"],Response::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Publication  $publication
     * @return \Illuminate\Http\Response
     */
    public function destroy(Publication $publication)
    {
        //Getting user data by token
        $user = auth()->user();
        //Validate if user is admin
        if($user->id_role === 1){
            
            $image = new ImageController();
            // delete all images from publication
            $image->destroy($publication->id);
            // delete publication
            $publication->delete();
            // return response
            return response()->json(['message'=>'publication deleted successfully'], Response::HTTP_OK);
        }else{
            //Returning if user isn't admin
            return response()->json(['messaje'=>"unauthorized user"],Response::HTTP_UNAUTHORIZED);
        }
    }

    //Method to bring comments to a publication
    public function commentsByPublication($id)
    {
        $comment = new CommentPublicationController();
        //Query of all comments by id_publication
        $comments = $comment->comments($id);
        //Return publication comments
        return response()->json($comments, Response::HTTP_OK);
    }

    public function latest(){
        $publications = Publication::latest()
        ->take(3)
        ->get();

        //Touring publications array for inserting images by publication
        foreach($publications as $publication){
            //Query for all image where "id_publication"
            $publication->images = Image::select()->where('id_publication',$publication->id)->get();
        }


        return $publications;
    }
}
