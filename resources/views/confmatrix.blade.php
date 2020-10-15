@extends('template.template')

@section('title',$title)
@section('contentTitle',$title)

@section('content')
<div class="row">
    <div class="col-md-5">
        <form action="{{URL::to('/confmatrix')}}" method="post">
            @csrf
            <div class="form-group">
              <label for="">User</label>
              <select class="form-control" name="user" onchange="submit()">
                <option
                @isset($current_user)
                @else
                    selected
                @endisset
                >-- pilih user --</option>
                @isset($users)
                    @foreach ($users as $user)
                        <option value="{{$user->id}}"
                            @isset($current_user)
                                @if ($current_user->id == $user->id)
                                    selected
                                @endif
                            @endisset
                            >{{$user->username}} ({{$user->name}})</option>
                    @endforeach
                @endisset
              </select>
            </div>
        </form>
    </div>
</div>

@isset($confmatrix)
<div class="card text-left">
    <div class="card-body">
        @isset($current_user)
        <h4 class="card-title">{{$current_user->username}}</h4>
        @endisset
        @isset($tweets)
        <div class="row">
            <div class="col-md-3">Total Tweet</div>
            <div class="col-md-5">{{$tweets->count()}}</div>
        </div>
        <div class="row">
            <div class="col-md-3">Total Labeled Tweet</div>
            <div class="col-md-5">{{$tweets->whereNotNull('label')->count()}}</div>
        </div>
        @endisset
    @foreach ($confmatrix as $key=>$item)
    <hr>
    <div class="row">
            <div class="col-md-3">
                @switch($key)
                @case("pos")
                        Positif
                        @break
                        @case("neu")
                        Netral
                        @break
                        @case("neg")
                        Negatif
                        @break
                        @default
                        @endswitch
                    </div>
                    <div class="col-md-5">
                        <div class="row">
                            <div class="col-md-3">
                                Accuracy
                            </div>
                            <div class="col-md-5">
                                <strong>
                                    {{$item['acc']}}
                                </strong>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                Precision
                            </div>
                    <div class="col-md-5">
                        <strong>
                            {{$item['pre']}}
                        </strong>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        Recall
                    </div>
                    <div class="col-md-5">
                        <strong>
                            {{$item['rec']}}
                        </strong>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endisset
@endsection
