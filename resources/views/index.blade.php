@extends('template')

@section('title')
    タイトル
@endsection

@section('content')
    <section>
        <div class="container">
            <p class="row">
                <span id="info" class="col s8 center">地図を読み込んでいます・・・</span>
                <button id="reload" class="btn waves-effect waves-light col s2">更新</button>
                <button id="location" class="btn waves-effect waves-light col s2">現在地</button>
            </p>
            <div class="row">
                <div class="col s12 l6 offset-l3 m10 offset-m1">
                    <div id="map"></div>
                </div>
            </div>
        </div>
    </section>
    <section>
        <form class="container">
            <div class="row">
                <div class="col s12">
                    <label for="address">住所</label><input type="text" id="address">
                </div>
            </div>
            <div class="row">
                <div class="col s6">
                    <label for="latitude">経度</label><input type="text" name="latitude" id="latitude">
                </div>
                <div class="col s6">
                    <label for="longitude">経度</label><input type="text" name="longitude" id="longitude">
                </div>
            </div>
            <div class="row">
                <div class="col s12">
                    <ul class="tabs" id="position-type">
                        <li class="tab col s3">
                            <a href="#denshin" class="active green-text text-darken-3">
                                電信柱
                                <span>
                                    <img src="https://maps.google.com/mapfiles/ms/icons/red-dot.png" alt="電信柱アイコン"
                                         class="pin-icon">
                                </span>
                            </a>
                        </li>
                        <li class="tab col s3">
                            <a href="#denchu" class="green-text text-darken-3">
                                電柱
                                <span>
                                    <img src="https://maps.google.com/mapfiles/ms/icons/blue-dot.png" alt="電柱アイコン"
                                         class="pin-icon">
                                </span>
                            </a>
                        </li>
                        <li class="tab col s3">
                            <a href="#building" class="green-text text-darken-3">
                                局舎
                                <span>
                                    <img src="https://maps.google.com/mapfiles/ms/icons/green-dot.png" alt="局舎アイコン"
                                         class="pin-icon">
                                </span>
                            </a>
                        </li>
                        <li class="tab col s3">
                            <a href="#other" class="green-text text-darken-3">
                                その他
                                <span>
                                    <img src="https://maps.google.com/mapfiles/ms/icons/yellow-dot.png" alt="その他アイコン"
                                         class="pin-icon">
                                </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="container" id="denshin">
                <div class="row">
                    <div class="col s4">
                        <label for="shisen_denshin">支線</label><input type="text" name="denshin_shisen"
                                                                     id="shisen_denshin">
                    </div>
                    <div class="col s2">
                        <label for="denshin_type">種別</label>
                        <select id="denshin_type">
                            <option>支</option>
                            <option>幹</option>
                            <option></option>
                        </select>
                    </div>
                    <div class="col s6">
                        <label for="number_denshin">番号</label><input type="text" name="denshin_number"
                                                                     id="number_denshin">
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 input-field">
                        <label for="note_denshin">備考</label><input type="text" name="denshin_note" id="note_denshin">
                    </div>
                </div>
            </div>
            <div class="container" id="denchu">
                <div class="row">
                    <div class="col s4">
                        <label for="shisen_denchu">支線</label><input type="text" name="denchu_shisen" id="shisen_denchu">
                    </div>
                    <div class="col s2">
                        <label for="denchu_type">種別</label>
                        <select id="denchu_type">
                            <option>支</option>
                            <option>幹</option>
                            <option></option>
                        </select>
                    </div>
                    <div class="col s6">
                        <label for="number_denchu">番号</label><input type="text" name="denchu_number" id="number_denchu">
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 input-field">
                        <label for="note_denchu">備考</label><input type="text" name="denchu_note" id="note_denchu">
                    </div>
                </div>
            </div>
            <div class="container" id="building">
                <div class="row">
                    <div class="col s10">
                        <label for="name_building">ビル名</label><input type="text" name="building_name"
                                                                     id="name_building">
                    </div>
                    <div class="col s2">
                        <label for="building_type">種別</label>
                        <select id="building_type">
                            <option>ビル</option>
                            <option></option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 input-field">
                        <label for="note_building">備考</label><input type="text" name="building_note" id="note_building">
                    </div>
                </div>
            </div>
            <div class="container" id="other">
                <div class="row">
                    <div class="col s12 input-field">
                        <label for="note_other">備考</label><textarea name="other_note" id="note_other"
                                                                    class="materialize-textarea"></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <button id="register" type="button" class="btn waves-effect waves-light col s12">登録</button>
            </div>
        </form>
    </section>

    <div style="display: none">
        <a href="{{route('position.store')}}" id="submitURL"></a>
        <a href="{{route('position.list')}}" id="listURL"></a>
        <a href="{{route('position.show')}}" id="showURL"></a>
        <a href="{{route('position.destroy')}}" id="deleteURL"></a>
        <input type="hidden" value="{{csrf_token()}}" id="token">
    </div>
@endsection

@section('js-tail')
    <script src="https://maps.googleapis.com/maps/api/js?key={{config('googlemap.api-key')}}&v=weekly"></script>
    <script src="{{asset('/js/main.js')}}"></script>
@endsection
