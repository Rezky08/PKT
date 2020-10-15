@extends('template\template')

@section('contentTitle',$title)

@section('title',$title)

@section('contentTop')
    @parent

@endsection


@section('content')
<div class="row justify-content-center">

    <div class="col-md-5">
        <form action="" method="post">
            @csrf
            <label for="username">Username</label>
            <div class="form-group input-group">
              <input type="text" name="username" class="form-control" placeholder="masukan username yang ingin dicari ....">
              <div class="input-group-append">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search fa-sm"></i>
                </button>
              </div>
            </div>
            <small>username tanpa @ diawal</small>
        </form>
    </div>
</div>
@endsection
