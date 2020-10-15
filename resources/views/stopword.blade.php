@extends('template\template')

@section('title',$title)
@section('contentTitle',$title)

@section('contentTop')
<a href="{{URL::to('/stopword/tambah')}}"><button class="btn btn-primary">Tambah +</button></a>
@if (Session::has('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
  <strong>
      {{Session::get('success')}}
  </strong>
</div>

<script>
  $(".alert").alert();
</script>
@endif
@endsection

@section('content')
<div class="row justify-content-center">

<div class="col-md-12">
    <div class="table-responsive">

        <table class="table table-striped table-light table-hover">
            <tr class="thead-dark" align="center">
                <th>No</th>
                <th>Word</th>
                <th>Action</th>
            </tr>
            @empty($stopwords)
            <tr>
                <td colspan="4"> Tidak Ada Data</td>
            </tr>
            @endempty
            @foreach ($stopwords as $index => $item)
            <tr>
                <td>{{($stopwords->currentPage()-1)*$stopwords->perPage()+($index+1)}}</td>
                <td>{{$item->word}}</td>
                <td align="center">
                    <a href="{{URL::to('/stopword/'.$item->id.'/edit')}}">
                        <button class="btn btn-info">
                            <i class="fas fa-edit"></i>
                        </button>
                    </a>
                    <a href="{{URL::to('/stopword/'.$item->id.'/delete')}}">
                        <button class="btn btn-danger">
                            <i class="fas fa-trash"></i>
                        </button>
                    </a>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
</div>
@isset($stopwords)
<div class="row justify-content-center">
    <div class="col-md-5">
        {{$stopwords->links()}}
    </div>
</div>
@endisset
@endsection

