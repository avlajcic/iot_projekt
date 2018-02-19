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

Route::post('/addroom', function (Request $request) {
    $arduinoId = $request->arduino_id;
    $userId = $request->user_id;

    $room = Room::where(array('user_id' => $userId,'arduino_id' => $arduinoId ))->first();
    if ($room){
        if ($room->error){
            $room->error = false;
            $room->save();
            $responseArray = array('status' => 1, 'response' => 'Removed error for room.');
        }else {
            $responseArray = array('status' => 1, 'response' => 'Room already exists.');
        }
    }
    else {
        $user = User::where('id', $userId)->first();

        if ($user) {
            $newRoom = new Room();
            $newRoom->name = 'Soba' . $userId . $arduinoId;
            $newRoom->start_time = new DateTime('00:00:00');
            $newRoom->end_time = new DateTime('23:45:00');
            $newRoom->automatic = false;
            $newRoom->brightness = 250;
            $newRoom->arduino_id = $arduinoId;
            $newRoom->user_id = $user->id;
            $newRoom->error = false;

            $newRoom->save();

            $responseArray = array('status' => 1, 'response' => 'Room added.');
        } else {
            $responseArray = array('status' => 0, 'response' => 'Can\'t find user with that id.');
        }
    }

    return response(json_encode($responseArray))->header('Content-Type', 'application/json');
});

Route::post('/deleteroom', function (Request $request) {
    $arduinoId =  $request->arduino_id;
    $userId = $request->user_id;

    $user = User::where('id', $userId)->first();
    if ($user) {
        $room = Room::where(array('user_id' => $userId,'arduino_id' => $arduinoId ))->first();

        if ($room) {
            $room->delete();

            $responseArray = array('status' => 1, 'response' => 'Room deleted.');
        }else{
            $responseArray = array('status' => 0, 'response' => 'Can\'t find room.');
        }
    }else{
        $responseArray = array('status' => 0, 'response' => 'Can\'t find user with that id.');
    }


    return response(json_encode($responseArray))->header('Content-Type', 'application/json');
});

Route::post('/error', function (Request $request) {
    $arduinoId =  $request->arduino_id;
    $userId = $request->user_id;

    $room = Room::where(array('user_id' => $userId,'arduino_id' => $arduinoId ))->first();
    if ($room){
        $room->error = true;
        $room->save();
        $responseArray = array('status' => 1, 'response' => 'Error has been set for room.');
    }else {
        $responseArray = array('status' => 0, 'response' => 'Can\'t find user with that id.');
    }


    return response(json_encode($responseArray))->header('Content-Type', 'application/json');
});