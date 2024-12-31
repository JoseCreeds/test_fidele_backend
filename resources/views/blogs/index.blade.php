@extends('base')

@section('content')
    <div class="container">

        <h1 style="text-align: center">Liste complet</h1>
        {{-- <table class="table table-bordered m-3">
        <thead>
            <tr>
                <!--<th scope="col">#</th>-->
                <th scope="col">Title</th>
                <th scope="col">Description</th>
                <th scope="col">Images</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>

            @foreach ($blogs_sent as $blog)
                <tr>
                    <!--<th scope="row">1</th>-->
                    <td>{{ $blog->title }}</td>
                    <td>{{ $blog->description }}</td>
                    <td><a href="storage/uploads/{{ $blog->image }}" Download><img style="width: 50px; height:auto;" src="storage/uploads/{{ $blog->image }}" alt=""></a></td>
                    <td><a class="btn btn-success text-white" href="{{ route('update', ['id' => $blog->id]) }}">Update</a>
                        <a class="btn btn-danger text-white" href="{{ route('delete', ['id' => $blog->id]) }}">Delete</a>
                        <a
                            class="btn btn-primary text-white" href="{{ route('afficher', ['id' => $blog->id]) }}">Détail</a>
                    </td>
                </tr>
            @endforeach

        </tbody>
    </table> --}}
        <table class="table table-bordered m-3">
            <thead>
                <tr>
                    <!--<th scope="col">#</th>-->
                    <th scope="col">Title</th>
                    <th scope="col">Description</th>
                    <th scope="col">Images</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($blogs_sent as $blog)
                    <tr>
                        <!--<th scope="row">1</th>-->
                        <td>{{ $blog->title }}</td>
                        <td>{{ $blog->description }}</td>
                        <td>
                            <a href="storage/uploads/{{ $blog->image }}" download>
                                <img class="img-fluid" src="storage/uploads/{{ $blog->image }}" alt="{{ $blog->title }}">
                            </a>
                        </td>
                        <td style="text-align: center">
                            <a class="btn btn-success text-white" href="{{ route('update', ['id' => $blog->id]) }}"
                                style="margin: 0 auto;">Update</a>
                            <a class="btn btn-danger text-white" href="{{ route('delete', ['id' => $blog->id]) }}"
                                style="margin: 0 auto;">Delete</a>
                            <a class="btn btn-primary text-white" href="{{ route('afficher', ['id' => $blog->id]) }}"
                                style="margin: 0 auto;">Détail</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>


        <p class="text-dark>{{ $blogs_sent->links() }}</p>
    </div>
@endsection
