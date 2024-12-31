@extends('base')

@section('content')
    <div class="container">
        <form action="" method="post" enctype="multipart/form-data">
            @csrf
            <h1>Ajouter un blog</h1>
            <div class="form-group">
                <label for="inputAddress">Titre</label>
                <input type="text" class="form-control" id="inputAddress" name="title" placeholder="">
            </div>
            <div class="form-group">
                <label for="inputAddress2">Description</label>
                <input type="text" class="form-control" id="inputAddress2" name="description" placeholder="">
            </div>
            <div class="form-group">
                <label for="inputAddress2">Fichier</label>
                <input type="file" class="form-control" id="#" name="image" placeholder="">
            </div>

            <button type="submit" class="btn btn-primary">Insérer</button>
        </form>
    </div>
@endsection
