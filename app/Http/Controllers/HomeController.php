<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Room;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home', ['rooms' => Room::orderBy('id')->get()]);
    }

    public function timeSettings(Request $request)
    {

        foreach ($request->room_id as $index => $roomId){
            $room = Room::find($roomId);
            $timeInput = explode(',', $request->time_schedule[$index]);
            $room->start_time = $this->formatTime($timeInput[0]);
            $room->end_time = $this->formatTime($timeInput[1]);
            if (in_array($request->room_id[$index], $request->automatic)){
                $room->automatic = true;
            }else{
                $room->automatic = false;
            }

            $room->save();
        }

        return redirect('home');
    }

    public function brightnessSettings(Request $request)
    {

        foreach ($request->room_id as $index => $roomId){
            $room = Room::find($roomId);
            $room->brightness = $request->light_schedule[$index];

            $room->save();
        }

        return redirect('home');
    }

    public function roomNameSettings(Request $request)
    {
        $room = Room::find($request->room_id);
        if (isset($request->delete)) {
            $room->delete();
        } else {
            $room->name = $request->name;
            $room->save();
        }

        return redirect('home');
    }


    public function formatTime($time)
    {
        $result = explode('.', $time);
        if (isset($result[1])){
            $minutes = strtr($result[1], array(
               '25' => '15',
               '5' => '30',
               '75' => '45',
            ));
            $stringTime = $result[0] . ':' . $minutes .':00';
        }else{
            $stringTime = $result[0] . ':00:00';
        }
        return new \DateTime($stringTime);
    }
}
