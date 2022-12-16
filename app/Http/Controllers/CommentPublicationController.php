<?php

namespace App\Http\Controllers;

use App\Models\Comment_Publication;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class CommentPublicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required',
            'id_publication' => 'required'
        ]);

        $user = auth()->user();

        if($user){
            $comment = new Comment_Publication();

            $comment->description = $request->description;
            $comment->id_publication = $request->id_publication;
            $comment->id_user = $user->id;

            $res = $comment->save();

            if($res){
                return response()->json(["message"=>"comment saved successfully"],Response::HTTP_OK);
            }else{
                return response()->json(['message'=>'comment not saved correctly'],Response::HTTP_ERROR);
            }
        }else{
            return response()->json(['menssaje'=>"unauthorized user"],Response::HTTP_UNAUTHORIZED);;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comment_Publication  $comment_Publication
     * @return \Illuminate\Http\Response
     */
    public function show(Comment_Publication $comment_Publication)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Comment_Publication  $comment_Publication
     * @return \Illuminate\Http\Response
     */
    public function comments($id)
    {
        $comments = Comment_Publication::all()->where('id_publication', $id);

        return $comments;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment_Publication  $comment_Publication
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        $request->validate([
            'description'=>'required'
        ]);

        $user = auth()->user();
        $comment = Comment_Publication::findOrFail($id);
        if($user->id === $comment->id_user){
            $comment->description = $request->description;

            $res = $comment->save();

            if($res){
                return response()->json(['message'=>'comment updated successfully'],Response::HTTP_OK);
            }{
                return response()->json(['message'=>'comment not updated correctly'],Response::HTTP_ERROR);
            }
        }else{
            return response()->json(['messaje'=>"unauthorized user"],Response::HTTP_UNAUTHORIZED);;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment_Publication  $comment_Publication
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $comment = Comment_Publication::findOrFail($id);
        $user = auth()->user();
        if($user->id === $comment->id_user){

            $res = $comment->delete();
    
            if($res){
               return response()->json(['message'=>'comment deleted successfully'],Response::HTTP_OK);
            }else{
                return response()->json(['message'=>'comment not deleted correctly'],Response::HTTP_ERROR);
            }
        }else{
            return response()->json(['messaje'=>"unauthorized user"],Response::HTTP_UNAUTHORIZED);;
        }
    }
}
