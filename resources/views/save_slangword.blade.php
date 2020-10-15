@extends('template\template')

@section('title',$title)
@section('contentTitle',$title)

@section('content')
<form action="" method="post">
    @csrf
    <div class="form-group">
      <label for="">Slang</label>
    <input type="text" class="form-control" name="slang" placeholder="masukan kata slang ..."
        @if(isset($slangword))
            value="{{old('slang',$slangword->slang)}}"
        @endif
        @if(!isset($slangword))
            value="{{old('slang')}}"
        @endif
    >
      @if ($errors->has('slang'))
        @error('slang')
            <small class="text-danger">{{$message}}</small>
        @enderror
      @endif
    </div>
    <div class="form-group">
      <label for="">Mean</label>
      <input type="text" class="form-control" name="mean" placeholder="maksud kata slang tersebut ..."
        @if(isset($slangword))
        value="{{old('mean',$slangword->mean)}}"
        @endif
        @if(!isset($slangword))
            value="{{old('mean')}}"
        @endif
      >
      @if ($errors->has('mean'))
        @error('mean')
            <small class="text-danger">{{$message}}</small>
        @enderror
      @endif
    </div>
    <a href="{{URL::to('/slangword')}}" class="float-right mx-2"><button type="button" class="btn btn-warning">Batal</button></a>
    <button type="submit" name="simpan" class="btn btn-primary float-right">Simpan</button>
</form>
@endsection

