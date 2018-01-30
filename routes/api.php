<?php

use Illuminate\Http\Request;
use App\User;
use App\Room;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/getroom', function (Request $request) {
    $room  = Room::where('user_id', $request->user_id)
            ->where('arduino_id', $request->arduino_id)
            ->first();

    return response(json_encode($room))->header('Content-Type', 'application/json');
});

Route::post('/setrooms', function (Request $request) {
    $numberOfRooms = (int) $request->rooms;
    $userId = $request->user_id;

    $user = User::where('id', $userId)->first();

    if ($user) {
        $rooms = Room::all();
        foreach ($rooms as $room){
            $room->delete();
        }
        for ($i = 0; $i < $numberOfRooms; $i++) {
            $newRoom = new Room();
            $newRoom->name = 'Soba' . $i;
            $newRoom->start_time = new DateTime('00:00:00');
            $newRoom->end_time = new DateTime('23:45:00');
            $newRoom->automatic = false;
            $newRoom->brightness = 255;
            $newRoom->arduino_id = $i;
            $newRoom->user_id = $user->id;

            $newRoom->save();
        }
        $responseArray = array('status' => 1, 'response' => 'Rooms added.');
    }else{
        $responseArray = array('status' => 0, 'response' => 'Can\'t find user with that id.');
    }


    return response(json_encode($responseArray))->header('Content-Type', 'application/json');
});