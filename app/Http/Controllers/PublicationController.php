<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use App\Models\Image;
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
        $publications = Publication::All();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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

            $image = new Image();

            $image->imagesAll($request->images, $publication->id);

            return response()->json(['message'=>'the Publication was saved successfully'], Response::HTTP_OK);

        }else{
            return response()->json(['message'=>'User Not Authorized'], Response::HTTP_UNAUTHORIZED);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Publication  $publication
     * @return \Illuminate\Http\Response
     */
    public function destroy(Publication $publication)
    {
        $image = new Image();
        $image->destroy($publication->id);

        $publication->delete();

        return response()->json(['message'=>'publication deleted successfully'], Response::HTTP_OK);
    }
}
