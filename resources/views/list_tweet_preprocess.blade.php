@extends('template\template')

@section('contentTitle',$title)

@section('title',$title)

@section('contentTop')
    @parent
    @isset($current_user)
        <a href="{{URL::to('/preprocessing/'.$current_user->id.'/processed')}}">
            <button type="button" class="btn btn-info"> Run Preprocessing</button>
        </a>
    @endisset
@endsection


@section('content')


<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="row my-2">
            <div class="col-md-5">
                <form action="{{URL::to('/preprocessing')}}" method="post">
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
                        <th>Tweet Preprocessed</th>
                        <th>Label</th>
                    </tr>
                </thead>
                    <tbody>
                    @isset($tweets)
                    @foreach ($tweets as $index=>$tweet)
                    <tr>
                        <td width="5%">{{($tweets->currentPage()-1)*$tweets->perPage()+($index+1)}}</td>
                        <td width="5%">{{$tweet->user->name}}</td>
                        <td width="5%">{{$tweet->user->username}}</td>
                        <td width="25%" class="text-justify">{{$tweet->textTweet}}</td>
                        <td width="25%" class="text-justify">{{$tweet->preprocess?$tweet->preprocess->textTweet:"Belum Ada"}}</td>
                        <td>
                            <form action="{{URL::to('/preprocessing/'.$tweet->user->id.'/'.$tweet->id.'/label')}}" method="post">
                                @csrf
                                <input type="hidden" name="id" value="{{$tweet->id}}">
                                <div class="form-group">
                                  <select
                                    @switch($tweet->label)
                                        @case("pos")
                                            class="form-control bg-success text-white"
                                            @break
                                        @case("neu")
                                            class="form-control bg-info text-white"
                                            @break
                                        @case("neg")
                                            class="form-control bg-danger text-white"
                                            @break
                                        @default
                                            class="form-control"
                                    @endswitch
                                   name="label" onchange="submit()">
                                    <option value=""
                                    @if (!$tweet->label)
                                        selected
                                    @endif>-- Beri Label --</option>
                                    <option value="pos"
                                    @if ($tweet->label == "pos")
                                        selected
                                    @endif
                                    ><strong>Positif</strong></option>
                                    <option value="neu"
                                    @if ($tweet->label == "neu")
                                    selected
                                    @endif
                                    ><strong>Netral</strong></option>
                                    <option value="neg"
                                    @if ($tweet->label == "neg")
                                    selected
                                    @endif
                                    ><strong>Negatif</strong></option>
                                  </select>
                                </div>
                            </form>
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
