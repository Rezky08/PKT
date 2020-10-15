@extends('template\template')

@section('title',$title)
@section('contentTitle',$title)

@section('contentTop')
<a href="{{URL::to('/slangword/tambah')}}"><button class="btn btn-primary">Tambah +</button></a>
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
                <th>Slang</th>
                <th>Mean</th>
                <th>Action</th>
            </tr>
            @empty($slangwords)
            <tr>
                <td colspan="4"> Tidak Ada Data</td>
            </tr>
            @endempty
            @foreach ($slangwords as $index => $item)
            <tr>
                <td>{{($slangwords->currentPage()-1)*$slangwords->perPage()+($index+1)}}</td>
                <td>{{$item->slang}}</td>
                <td>{{$item->mean}}</td>
                <td align="center">
                    <a href="{{URL::to('/slangword/'.$item->id.'/edit')}}">
                        <button class="btn btn-info">
                            <i class="fas fa-edit"></i>
                        </button>
                    </a>
                    <a href="{{URL::to('/slangword/'.$item->id.'/delete')}}">
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
@isset($slangwords)
<div class="row justify-content-center">
    <div class="col-md-5">
        {{$slangwords->links()}}
    </div>
</div>
@endisset
@endsection

