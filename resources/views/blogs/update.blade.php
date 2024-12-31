@extends('base')

@section('content')
    <div class="container">
        <form action="" method="post">
            @csrf

      
            <div class="form-group">
                <label for="inputAddress">Titre</label>
                <input type="text" class="form-control" id="inputAddress" name="title" value="{{$bog_updating->title}}">
            </div>
            <div class="form-group">
                <label for="inputAddress2">Description</label>
                <input type="text" class="form-control" id="inputAddress2" name="description" value="{{$bog_updating->description}}">
            </div>
 

            <button type="submit" class="btn btn-primary">Insérer</button>
        </form>
    </div>
@endsection
