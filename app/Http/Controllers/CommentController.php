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
        //Getting user data by token
        $user = auth()->user();
        //Validate if user is logged
        if($user){
            //Validating data
            $request->validate([
                "description" => 'required',
                'id_event' => 'required'
            ]);
            //Inserting data
            $comment = new Comment();
            $comment->description = $request->description;
            $comment->id_user = $user->id;
            $comment->id_event = $request->id_event;
            //Save comment
            $res = $comment->save();
            //Validating if comment has been saved correctly
            if($res){
                return response()->json(['message' => 'comment saved successfully'], Response::HTTP_OK);
            }else{
                return response()->json(['message' => 'comment has not been saved correctly'], Response::HTTP_ERROR);
            }
        }else{
            //Return if user isn't admin
            return response()->json(['messaje'=>"unauthorized user"],Response::HTTP_UNAUTHORIZED); 
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
        //Count all comments by publication
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
        //Comments of an event
        $comment = Comment::select('comments.*','users.name as user')->where('id_event',$id)
                    ->join('users', 'users.id','=','id_user')->orderBy('created_at', "DESC")->get();
        //Return comments
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
        //Getting user data by token
        $user = auth()->user();
        //Bringing comments by id
        $comment = Comment::findOrFail($id);
        //Validate if user is the same who commented
        if($user->id === $comment->id_user){
            //Inserting data
            $comment->description = $request->description;
            //Updating comment
            $res = $comment->save();
            //Validating if comment has been updated correctly
            if($res){
                return response()->json(['message' => 'comment updated successfully'], Response::HTTP_OK);
            }else{
                return response()->json(['message' => 'comment not updated correctly'], Response::HTTP_ERROR);
            }
        }else{
            //Return if user isn't authorized
            return response()->json(['messaje'=>"unauthorized user"],Response::HTTP_UNAUTHORIZED);
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
        //Getting user data by token
        $user = auth()->user();
        //Validate if user is the same who commented or admin
        if($user->id === $comment->id_user || $user->id === 1){
            //Delete comment
            $res = $comment->delete();
            //Validate if comment has been deleted correctly
            if($res){
                return response()->json(['message' => 'comment deleted successfully'], Response::HTTP_OK);
            }else{
                return response()->json(['message' => 'comment not deleted correctly'], Response::HTTP_ERROR);
            }
        }else{
            //Return if user isn't authorized
            return response()->json(['messaje'=>"unauthorized user"],Response::HTTP_UNAUTHORIZED);
        }
    }
}
