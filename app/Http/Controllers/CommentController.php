<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $comments = Comment::All();
        // return $comments;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        if($user){

            $request->validate([
                "description" => 'required',
                'id_event' => 'required'
            ]);

            $comment = new Comment();
            $comment->description = $request->description;
            $comment->id_user = $user->id;
            $comment->id_event = $request->id_event;
        
            $res = $comment->save();

            if($res){
                return response()->json(['message' => 'comment saved successfully'], Response::HTTP_OK);
            }else{
                return response()->json(['message' => 'comment not saved correctly'], Response::HTTP_ERROR);
            }
        }else{
            return response()->json(['messaje'=>"unauthorized user"],Response::HTTP_UNAUTHORIZED);;
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function count($id)
    {
        $comment = Comment::all()->count();

        return $comment;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function comments($id)
    {
        $comment = Comment::all()->where('id_event',$id);

        return response()->json($comment,Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();

        $comment = Comment::findOrFail($id);
        if($user->id === $comment->id_user){
            $comment->description = $request->description;
    
            $res = $comment->save();
            
            if($res){
                return response()->json(['message' => 'comment updated successfully'], Response::HTTP_OK);
            }else{
                return response()->json(['message' => 'comment not updated correctly'], Response::HTTP_ERROR);
            }
        }else{
            return response()->json(['messaje'=>"unauthorized user"],Response::HTTP_UNAUTHORIZED);;
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        $user = auth()->user();
        if($user->id === $comment->id_user){
            $res = $comment->delete();

            if($res){
                return response()->json(['message' => 'comment deleted successfully'], Response::HTTP_OK);
            }else{
                return response()->json(['message' => 'comment not deleted correctly'], Response::HTTP_ERROR);
            }
        }else{
            return response()->json(['messaje'=>"unauthorized user"],Response::HTTP_UNAUTHORIZED);;
        }
    }
}
