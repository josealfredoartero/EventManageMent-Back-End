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
        //Validate data
        $request->validate([
            'description' => 'required',
            'id_publication' => 'required'
        ]);
        //Getting user data by token
        $user = auth()->user();
        //Validate if user is logged
        if($user){
            $comment = new Comment_Publication();
            //Inserting data
            $comment->description = $request->description;
            $comment->id_publication = $request->id_publication;
            $comment->id_user = $user->id;
            //Save comment
            $res = $comment->save();
            //Validate if comment has been saved correctly
            if($res){
                return response()->json(["message"=>"comment saved successfully"],Response::HTTP_OK);
            }else{
                return response()->json(['message'=>'comment not saved correctly'],Response::HTTP_ERROR);
            }
        }else{
            //Return if user isn't logged
            return response()->json(['menssaje'=>"unauthorized user"],Response::HTTP_UNAUTHORIZED);
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
        //Query all comments by id_publication
        $comments = Comment_Publication::select('comment__publications.*','users.name as user')->where('id_publication', $id)
                    ->join('users', 'users.id','=','id_user')->orderBy('created_at','DESC')->get();
        //Return comments
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
        //Validating data
        $request->validate([
            'description'=>'required'
        ]);
        //Getting user data by token
        $user = auth()->user();
        //Find comment by id_publication
        $comment = Comment_Publication::findOrFail($id);
        //Validate if user is the same who commented
        if($user->id === $comment->id_user){
            //Inserting data
            $comment->description = $request->description;
            //Update comment
            $res = $comment->save();
            //Validating if comment has been updated correctly
            if($res){
                return response()->json(['message'=>'comment updated successfully'],Response::HTTP_OK);
            }{
                return response()->json(['message'=>'comment not updated correctly'],Response::HTTP_ERROR);
            }
        }else{
            //Return if user isn't authorized
            return response()->json(['messaje'=>"unauthorized user"],Response::HTTP_UNAUTHORIZED);
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
        //Find comment by id
        $comment = Comment_Publication::findOrFail($id);
        //Getting user data by token
        $user = auth()->user();
        //Validate if user is the same who commented or admin
        if($user->id === $comment->id_user || $user->id === 1){
            //Delete comment
            $res = $comment->delete();
            //Validate if comment has been deleted correctly
            if($res){
               return response()->json(['message'=>'comment deleted successfully'],Response::HTTP_OK);
            }else{
                return response()->json(['message'=>'comment not deleted correctly'],Response::HTTP_ERROR);
            }
        }else{
            //Return if user isn't authorized
            return response()->json(['messaje'=>"unauthorized user"],Response::HTTP_UNAUTHORIZED);
        }
    }
}
