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

        $publications = Publication::all();

        foreach($publications as $publication){
            $publication->images = Image::all()->where('id_publication',$publication->id);
        }
        return $publications;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|min:3|max:50',
            'description' => 'required',
            'images' => 'required'
        ]);

        $user = auth()->user();
        if($user->id_role = 1){
            $publication = new Publication;
    
            $publication->title = $request->title;
            $publication->description = $request->description;
            $publication->id_user = $user->id;

            $publication->save();

            $image = new ImageController();

            $image->store($request->images, $publication->id);

            return response()->json(['message'=>'the Publication was saved successfully'], Response::HTTP_OK);

        }else{
            return response()->json(['menssaje'=>"unauthorized user"],Response::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Publication  $publication
     * @return \Illuminate\Http\Response
     */
    public function show(Publication $publication)
    {
        $publication->images = Image::where('id_publication', $publication->id)->get();
        
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
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);
        $user = auth()->user();
        if($user->id_role === 1){
            $publication->title = $request->title;
            $publication->description = $request->description;
    
            $image = new ImageController();
            if($request->images){
                if($request->images->addImages){
                    $image->store($request->images->addImages,$publication->id);
                }
                if($request->images->deleteImages){
                    foreach($request->images->deleteImage as $images){
                        $image->deleteImg($images->id);
                    }
                }
            }
            $res = $publication->save();
    
            return response()->json(['message'=>"publication modified successfully"]);
        }else{
            return response()->json(['menssaje'=>"unauthorized user"],Response::HTTP_UNAUTHORIZED);;
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
        $image = new ImageController();
        $image->destroy($publication->id);

        $publication->delete();

        return response()->json(['message'=>'publication deleted successfully'], Response::HTTP_OK);
    }

    public function commentsByPublication($id)
    {
        $comments = CommentPublicationController::comments($id);

        return response()->json($comments, Response::HTTP_OK);
    }
}
