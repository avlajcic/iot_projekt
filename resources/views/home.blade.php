@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

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
                        <h4>Soba 1</h4>
                        <label for="ex2">Vrijeme rada: </label>
                        <b>0</b>
                        <input id="ex2" type="text" class="span2" value=""
                               data-slider-min="0" data-slider-max="24"
                               data-slider-step="0.25" data-slider-value="[7,22]" name="time-schedule"/>
                        <b>24</b>
                        <br>
                        <label for="automaticCheck">Automatski rad:</label>
                        <input id="automaticCheck" type="checkbox" name="automatic" value="automatic"><br>
                    </div>

                    <div id="brightness-settings">
                        <h4>Soba 1</h4>
                        <label for="ex1">Jačina svjetla: </label>
                        <input id="ex1" data-slider-id='ex1Slider' type="text" name="light-schedule"
                               data-slider-min="0" data-slider-max="255" data-slider-step="1" data-slider-value="255"/>
                        <br>
                    </div>

                    <div id="room-settings">
                        <h3>Sobe:</h3>
                        <div class="row">
                            <div class="col-sm-4 col-md-3">
                                <p>Soba 1</p>
                            </div>
                            <div class="col-sm-4 col-sm-offset-4">
                                <button type="button" class="btn btn-default">Preimenuj</button>
                                <button type="button" class="btn btn-danger">Izbriši</button>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-4 col-md-3">
                                <p>Soba 2</p>
                            </div>
                            <div class="col-sm-4 col-sm-offset-4">
                                <button type="button" class="btn btn-default">Preimenuj</button>
                                <button type="button" class="btn btn-danger">Izbriši</button>
                            </div>
                        </div>
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
            $("#ex2").slider({
                tooltip_split: true,
                formatter: function(value) {
                    var minutes = value - Math.floor(value);
                    var hours = Math.floor(value);
                    if (minutes !== 0) {
                        return hours + ':' + minutes * 60;
                    }else{
                        return hours + ':00';
                    }
                }
            });
            $('#ex1').slider({
                formatter: function(value) {
                    return 'Trenutna jačina: ' + value;
                }
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
        })
    </script>
@endsection
