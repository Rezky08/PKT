@extends('template\template')

@section('contentTitle',$title)

@section('title',$title)

@section('contentTop')
    @parent
    @isset ($current_user)
        <a href="{{URL::to('/tweet/'.$current_user->id.'/getSentiment')}}">
            <button type="button" class="btn btn-info"> Dapatkan arah sentiment tweet</button>
        </a>
    @endisset
@endsection


@section('content')


<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="row my-2">
            <div class="col-md-5">
                <form action="{{URL::to('/tweet')}}" method="post">
                @csrf
                <label for="user">Akun</label>
                <div class="input-group">
                    <select class="form-control" name="user">
                    <option disabled
                    @if (!isset($current_user))
                        selected
                    @endif
                    >-- Pilih Akun --</option>
                    @isset($users)
                        @foreach ($users as $index=>$user)
                        <option value="{{$index}}"
                        @isset($current_user)
                            @if ($current_user->id == $user->id)
                            selected
                            @endif
                        @endisset
                        >{{$user->username}}  ({{$user->name}})</option>
                        @endforeach
                    @endisset
                    </select>
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-info" name="search" value="1">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                </form>
            </div>
        </div>
        <div class="table-responsive">

            <table class="table table-striped table-hover">
                <thead>

                    <tr class="thead-dark">
                        <th>No</th>
                        <th>Nama Pengirim</th>
                        <th>Username</th>
                        <th>Tweet</th>
                        <th>Arah Sentiment</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                @isset($tweets)
                @foreach ($tweets as $index=>$tweet)
                <tr>
                    <td>{{($tweets->currentPage()-1)*$tweets->perPage()+($index+1)}}</td>
                    <td>{{$current_user->name}}</td>
                    <td>{{$current_user->username}}</td>
                    <td>{{$tweet->textTweet}}</td>
                    <td>
                        @isset($tweet->score)
                        @switch($tweet->score->label)
                        @case("pos")
                        <div class="btn bg-success">
                            <strong class="text-white">Positif</strong>
                        </div>
                        @break
                            @case("neg")
                            <div class="btn bg-danger">
                                <strong class="text-white">Negatif</strong>
                            </div>
                            @break
                            @case("neu")
                            <div class="btn bg-info">
                                <strong class="text-white">Netral</strong>
                            </div>
                            @break
                            @default

                            @endswitch
                            @else
                            Belum Ada
                            @endisset
                        </td>
                        <td>
                            <a  href="{{URL::to('/tweet/'.$current_user->id.'/'.$tweet->id)}}">
                                <button type="button" class="btn btn-info"
                                @if (!isset($tweet->score))
                                disabled
                                @endif
                                >Score Detail</button>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                    @endisset
                </tbody>
            </table>
        </div>

        @isset($tweets)
        <div class="row justify-content-center">
            <div class="col-md-8">
                {{$tweets->links()}}
            </div>
        </div>
        @endisset

    </div>
</div>
@endsection
