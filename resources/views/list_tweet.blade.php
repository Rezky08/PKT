@extends('template\template')

@section('contentTitle',$title)

@section('title',$title)

@section('topbar')
    @parent
    <div class="alert alert-warning alert-dismissible fade show my-2" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
      <strong>Jika ingin menyimpan Tweet, maka klik tombol "Simpan"</strong>
    </div>

    <script>
      $(".alert").alert();
    </script>
@endsection

@section('contentTop')
    @parent
    <form action="{{URL::to('/twitter/'.$profile->screen_name)}}" method="post">
    @csrf
    <button class="btn btn-primary" name="simpan_tweet">Simpan</button>
    <input type="hidden" name="idUser" value="{{$profile->id_str}}">
    <input type="hidden" name="username" value="{{$profile->screen_name}}">
    <input type="hidden" name="name" value="{{$profile->name}}">
    <a href="{{URL::to('/twitter')}}"><button class="btn btn-warning" type="button">Kembali</button></a>
@endsection


@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">


        <table class="table table-striped table-hover">
            <tr class="thead-dark">
                <th>No</th>
                <th>Nama Pengirim</th>
                <th>Username</th>
                <th>Tweet</th>
            </tr>
            @isset($tweets)
            @foreach ($tweets as $index=>$tweet)
            <tr>
                <td>{{$index+1}}</td>
                <td>{{$tweet->user->name}}</td>
                <td>{{$tweet->user->screen_name}}</td>
                <td>{{$tweet->full_text}}</td>
                <input type="hidden" name="idTweet[]" value="{{$tweet->id_str}}">
                <input type="hidden" name="textTweet[]" value="{{$tweet->full_text}}">
            </tr>
            @endforeach
            @endisset
        </table>

        </form>

    </div>
</div>
@endsection
