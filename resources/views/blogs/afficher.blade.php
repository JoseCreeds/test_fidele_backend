@extends('base')

@section('content')
    <div class="container">
        <h1>{{ $blog->title }}</h1>
        <p>{{$blog->description}}</p>
    </div>
@endsection
