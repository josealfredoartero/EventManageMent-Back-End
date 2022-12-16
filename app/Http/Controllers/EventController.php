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
        $events = Event::All();
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
        $request->validate([
            'title' => 'required|min:3|max:50',
            'description' => 'required',
            'date' => 'required',
            'image' => 'required'
        ]);

        $user = auth()->user();

        if($user->id_role === 1){
            $event = new Event();
            $event->title = $request->title;
            $event->description = $request->description;
            $event->date = $request->date;
            $event->id_user = $user->id;

            $image = new ImageController();
            $link = $image->decodeImg($request->image);
            
            $event->image = $link;

            $res = $event->save();

            if($res){
                return response()->json(['message' => 'the event was saved successfully'],Response::HTTP_OK);
            }else{
                return response()->json(['message' => 'event not saved correctly'],Response::HTTP_REQUEST_HEADER_FIELDS_TOO_LARGE);
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
    public function show($id)
    {
        
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
        $request->validate([
            'title' => 'required|min:3|max:50',
            'description' => 'required',
            'date' => 'required|date',
        ]);

        $user = auth()->user();

        if($user->id_role === 1 ){

            $event->title = $request->title;
            $event->description = $request->description;
            $event->date = $request->date;
            $event->id_user = $user->id;
            if($request->image){
                $image = new ImageController();
                $image->deleteEvent($event->image);

                $link = $image->decodeImg($request->image);
                $event->image = $link;
            }

            $res = $event->save();
            if($res){
                return response()->json(['message' => 'the event was updated successfully'],Response::HTTP_OK);
            }else{
                return response()->json(['message' => 'event not updated correctly'],Response::HTTP_REQUEST_HEADER_FIELDS_TOO_LARGE);
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
    public function destroy(Event $event)
    {
        $user = auth()->user();

        if($user->id_role){
            $image = new ImageController();
            $res = $image->deleteEvent($event->image);
            if($res){
                $res = $event->delete();
                if($res){
                    return response()->json(['message'=>'event deleted'], Response::HTTP_OK);
                }else{
                    return response()->json(['message'=>'event not deleted correctly'], Response::HTTP_ERROR);
                }
            }
        }else{
            return response()->json(['messaje'=>"unauthorized user"],Response::HTTP_UNAUTHORIZED);;
        }
    }
}
