<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Validator;

use App\Models\Event;

class EventsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $titleTag = 'Events | ';
        $h2Tag = 'Events';

        return view('events.index', compact('titleTag', 'h2Tag'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $titleTag = 'Create Event | ';
        $h2Tag = 'Create Event';

        return view('events.create', compact('titleTag', 'h2Tag'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            
            'name' => 'required',
            'location' => 'required',
            'date' => 'required|date',
            'url' => 'required'
        ]);

        $name = trim($request->input('name'));
        $location = trim($request->input('location'));
        $date = trim($request->input('date'));
        $url = trim($request->input('url'));

        $event = Event::where('name', $name)
                      ->where('location', $location)
                      ->where('date', $date)
                      ->first();

        if ($event) {

            $message = 'This event already exists in the database.';

            $request->flash();

            return redirect()->route('events.create')->with('message', $message);
        }

        $event = new Event;

        $event->name = $name;
        $event->location = $location;
        $event->date = $date;
        $event->url = $url;

        $event->save();

        $message = 'Success!';

        return redirect()->route('events.index')->with('message', $message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
