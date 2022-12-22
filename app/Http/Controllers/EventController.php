<?php

namespace App\Http\Controllers;
use App\Models\Event;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\ImageController;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        //Query for all events
        $events = Event::select()->orderBy('created_at',"DESC")->get();

        //Return of all events
        return response()->json($events, Response::HTTP_OK);

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
        //Validating data
        $request->validate([
            'title' => 'required|min:3|max:50',
            'description' => 'required',
            'date' => 'required',
            'image' => 'required'
        ]);
        
        //Getting user data by token
        $user = auth()->user();

        //Condition if user is admin
        if($user->id_role === 1){
            //Inseting data for new event
            $event = new Event();
            $event->title = $request->title;
            $event->description = $request->description;
            $event->date = $request->date;
            $event->id_user = $user->id;
            //Instance image controller
            $image = new ImageController();
            //Save image in the storage and getting link
            $link = $image->decodeImg($request->image);
            
            $event->image = $link;
            //Saving the response "save"
            $res = $event->save();
            //Validating the response
            if($res){
                return response()->json(['message' => 'the event was saved successfully'],Response::HTTP_OK);
            }else{
                return response()->json(['message' => 'event not saved correctly'],Response::HTTP_REQUEST_HEADER_FIELDS_TOO_LARGE);
            }
        
        }else{
            //Returning if user isn't admin
            return response()->json(['messaje'=>"unauthorized user"],Response::HTTP_UNAUTHORIZED);
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $event = Event::findOrFail($id);

        return response()->json($event, Response::HTTP_OK);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Event $event)
    {
        //Validating data
        $request->validate([
            'title' => 'required|min:3|max:50',
            'description' => 'required',
            'date' => 'required|date',
        ]);

        //Getting user data by token
        $user = auth()->user();

        //Condition if user is admin
        if($user->id_role === 1 ){

            //Inserting data 
            $event->title = $request->title;
            $event->description = $request->description;
            $event->date = $request->date;
            $event->id_user = $user->id;
            //Validating if there is an image
            if($request->image){
                $image = new ImageController();
                //Delete image from storage
                $image->deleteEvent($event->image);
                //Save image in storage and getting link
                $link = $image->decodeImg($request->image);
                $event->image = $link;
            }
            //Saving the response "save"
            $res = $event->save();
            //Validating the response
            if($res){
                return response()->json(['message' => 'the event was updated successfully'],Response::HTTP_OK);
            }else{
                return response()->json(['message' => 'event not updated correctly'],Response::HTTP_REQUEST_HEADER_FIELDS_TOO_LARGE);
            }
        }else{
            //Return if user isn't admin
            return response()->json(['messaje'=>"unauthorized user"],Response::HTTP_UNAUTHORIZED);;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Event $event)
    {
        //Getting user by token
        $user = auth()->user();

        //Validating if user is admin
        if($user->id_role === 1){
            $image = new ImageController();
            //Delete image from storage
            $res = $image->deleteEvent($event->image);
            //Validating if image was deleted from storage
            if($res){
                //Delete event
                $res = $event->delete();
                if($res){
                    return response()->json(['message'=>'event deleted'], Response::HTTP_OK);
                }else{
                    return response()->json(['message'=>'event not deleted correctly'], Response::HTTP_ERROR);
                }
            }
        }else{
            //Returning if user isn't admin
            return response()->json(['messaje'=>"unauthorized user"],Response::HTTP_UNAUTHORIZED);;
        }
    }

    public function latest(){
        $events = Event::latest()
        ->take(3)
        ->get();
        return $events;
    }
}
