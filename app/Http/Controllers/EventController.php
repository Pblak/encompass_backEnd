<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function getEvents(Request $request)
    {
        return response()->json(Event::all());
    }

    public function getEvent(Request $request, $id)
    {
        return response()->json(Event::find($id));
    }

    public function updateEvent(Request $request, $id)
    {
        $event = Event::find($id);
        $event->update($request->all());
        return response()->json($event);
    }

    public function createEvent(Request $request)
    {
        $event = Event::create($request->all());
        return response()->json($event);
    }

}
