@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    @foreach ($errorRooms as $room)
                        <h3 style="color:red; text-align:center">{{ $room->name }} ima grešku!</h3>
                    @endforeach
                    <div class="row">
                        <div class="col-sm-6 col-md-4">
                            <a href="#time-settings" id="time-settings-button">
                                <div class="thumbnail">
                                    <div class="caption text-center">
                                        <h3>Postavke vremena</h3>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <a href="#brightness-settings" id="brightness-settings-button">
                                <div class="thumbnail">
                                    <div class="caption text-center">
                                        <h3>Postavke svjetline</h3>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <a href="#room-settings" id="room-settings-button">
                                <div class="thumbnail">
                                    <div class="caption text-center">
                                        <h3>Sobe</h3>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div id="time-settings">
                        <form method="post" action="/time-settings">
                        {{ csrf_field() }}
                        @foreach ($rooms as $room)
                            <h4 @if ($room->error) style="color:red" @endif >{{ $room->name }}</h4>
                            <label for="time-slider{{ $room->id }}">Vrijeme rada: </label>
                            <b>0</b>
                            <input id="time-slider{{ $room->id }}" type="text" class="span2 time-slider" value=""
                                   data-slider-min="0" data-slider-max="24"
                                   data-slider-step="0.25" data-slider-value="[7,22]" name="time_schedule[]"
                                   data-start-time="{{ $room->start_time }}"
                                   data-end-time="{{ $room->end_time }}"
                                />
                            <b>24</b>
                            <br>
                            <label for="automaticCheck">Automatski rad:</label>
                            <input id="automaticCheck" type="checkbox" name="automatic[]" value="{{ $room->id }}" @if ($room->automatic) checked @endif>
                            <input type="hidden" value="{{ $room->id }}" name="room_id[]">
                            <br>
                        @endforeach
                        <input type="submit" class="btn btn-success pull-right" value="Spremi" name="submit">
                        </form>
                    </div>

                    <div id="brightness-settings">
                        <form method="post" action="/brightness-settings">
                            {{ csrf_field() }}
                            @foreach ($rooms as $room)
                                <h4 @if ($room->error) style="color:red" @endif >{{ $room->name }}</h4>
                                <label for="brightness-slider{{ $room->id }}">Jačina svjetla: </label>
                                <input id="brightness-slider{{ $room->id }}" data-slider-id='ex1Slider' type="text" name="light_schedule[]" class="brightness-slider"
                                       data-slider-min="0" data-slider-max="10" data-slider-step="1" data-slider-value="{{ (int)$room->brightness/25 }}"/>
                                <input type="hidden" value="{{ $room->id }}" name="room_id[]">
                                <br>
                            @endforeach
                            <input type="submit" class="btn btn-success pull-right" value="Spremi" name="submit">
                        </form>
                    </div>

                    <div id="room-settings">
                        <h3>Sobe:</h3>
                            @foreach ($rooms as $room)
                                <form method="post" action="/room-name-settings">
                                    {{ csrf_field() }}
                                    <div class="row">
                                        <div class="col-sm-4 col-md-3">
                                            <p class="name{{ $room->id }}">{{ $room->name }}</p>
                                        </div>
                                        <div class="col-sm-4 col-sm-offset-4">
                                            <button type="button" class="btn btn-default rename" data-room="name{{ $room->id }}">Preimenuj</button>
                                            <input type="submit" class="btn btn-danger" data-room="name{{ $room->id }}" value="Izbriši" name="delete">
                                            <input type="hidden" value="{{ $room->id }}" name="room_id">
                                        </div>
                                    </div>
                                    <br>
                                </form>
                            @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <script>
        $(document).ready(function () {
            var timeSetting = $('#time-settings');
            var brightnessSetting = $('#brightness-settings');
            var roomSetting = $('#room-settings');
            timeSetting.hide();
            brightnessSetting.hide();
            roomSetting.hide();
            $('.time-slider').each(function () {
                var start = $.map( $(this).attr('data-start-time').split(':'), Number );
                var end = $.map( $(this).attr('data-end-time').split(':'), Number );
                start[1] = changeMinutes(start[1]);
                end[1] = changeMinutes(end[1]);
                start = start[0] + start[1] / 100;
                end = end[0] + end[1] / 100;

                $(this).slider({
                    tooltip_split: true,
                    formatter: function(value) {
                        var minutes = value - Math.floor(value);
                        var hours = Math.floor(value);
                        if (minutes !== 0) {
                            return hours + ':' + minutes * 60;
                        }else{
                            return hours + ':00';
                        }
                    },
                    value: [start, end],
                    tooltip: 'always'
                });

            });

            $('.brightness-slider').each(function () {
                $(this).slider({
                    formatter: function(value) {
                        return 'Trenutna jačina: ' + value;
                    }
                });
            });


            $('.rename').click(function(){
                var className = $(this).attr('data-room');
                var paragraph = $('.' + className);
                var value = paragraph.text();
                var new_html = (
                    '<input value="' + value + '" name="name" class="form-control">');
                paragraph.replaceWith(new_html);
                new_html = '<input type="submit" class="btn btn-success" value="Spremi" name="submit">';
                $(this).replaceWith(new_html);
            });


            $('#time-settings-button').click(function () {
                if ( timeSetting.is(':visible') ){
                    timeSetting.hide(1000);
                }else {
                    timeSetting.show(1000);
                    brightnessSetting.hide(1000);
                    roomSetting.hide(1000);
                }
            });

            $('#brightness-settings-button').click(function () {
                if ( brightnessSetting.is(':visible') ){
                    brightnessSetting.hide(1000);
                }else {
                    brightnessSetting.show(1000);
                    timeSetting.hide(1000);
                    roomSetting.hide(1000);

                }
            });

            $('#room-settings-button').click(function () {
                if ( roomSetting.is(':visible') ){
                    roomSetting.hide(1000);
                }else {
                    roomSetting.show(1000);
                    timeSetting.hide(1000);
                    brightnessSetting.hide(1000);

                }
            });


            function changeMinutes(minutes)
            {
                if (minutes === 15)
                    return 25;
                else if (minutes === 30)
                    return 50;
                else if (minutes === 45)
                    return 75;
                else return minutes;
            }
        })
    </script>
@endsection
