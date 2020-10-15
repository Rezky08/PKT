@extends('template\template')

@section('contentTitle',$title)

@section('title',$title)

@section('contentTop')
    @parent
    <a href="{{URL::to('/tweet')}}">
        <button type="button" class="btn btn-warning">
            Kembali
        </button>
    </a>
@endsection


@section('content')
<div class="card text-left">
  <img class="card-img-top" src="holder.js/100px180/" alt="">
  <div class="card-body">
    <div class="card-title row">
        <div class="col-md-2">
            <strong>Label</strong>
        </div>
        <div class="col-md-5">
            @switch($tweet->score->label)
                @case("pos")
                <div class="btn bg-success">
                    <strong class="text-white">Positif</strong>
                </div>

                @break
                @case("neu")
                    <div class="btn bg-info">
                        <strong class="text-white">Netral</strong>
                    </div>

                    @break
                @case("neg")
                <div class="btn bg-danger">
                    <strong class="text-white">Negatif</strong>
                </div>

                @break
                @default

            @endswitch
        </div>
    </div>
    <div class="row">
        <div class="col-md-2">Positif</div>
        <div class="col-md-5">: {{$tweet->score->positif}}</div>
    </div>
    <div class="row">
        <div class="col-md-2">Netral</div>
        <div class="col-md-5">: {{$tweet->score->netral}}</div>
    </div>
    <div class="row">
        <div class="col-md-2">Negatif</div>
        <div class="col-md-5">: {{$tweet->score->negatif}}</div>
    </div>
    <div class="row">
        <div class="col-md-2">Tweet</div>
        <div class="col-md-8">: {{$tweet->textTweet}}</div>
    </div>
  </div>
</div>
@endsection
