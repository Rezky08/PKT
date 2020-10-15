@extends('template\template')

@section('title',$title)
@section('contentTitle',$title)

@section('content')
<form action="" method="post">
    @csrf
    <div class="form-group">
      <label for="">Stopword</label>
    <input type="text" class="form-control" name="word" placeholder="masukan kata stop word ..."
        @if(isset($stopword))
            value="{{old('word',$stopword->word)}}"
        @endif
        @if(!isset($stopword))
            value="{{old('word')}}"
        @endif
    >
      @if ($errors->has('word'))
        @error('word')
            <small class="text-danger">{{$message}}</small>
        @enderror
      @endif
    </div>
    <a href="{{URL::to('/stopword')}}" class="float-right mx-2"><button type="button" class="btn btn-warning">Batal</button></a>
    <button type="submit" name="simpan" class="btn btn-primary float-right">Simpan</button>
</form>
@endsection

