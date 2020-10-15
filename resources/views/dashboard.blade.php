@extends('template\template')

@section('title',$title)

@section('contentTitle',$title)

@section('content')
<!-- Content Row -->
<div class="row">

    <!-- SlangWord Card -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Slang Word</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">{{$counts['slangword_count']}} Kata</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-font fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Stopword Card -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Stop Word</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{$counts['stopword_count']}} Kata</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-font fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- User Card -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">User</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{$counts['user_count']}} user</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-user fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Tweet Card -->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Tweet</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{$counts['tweet_count']}} Tweet</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-comment fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>

@endsection
