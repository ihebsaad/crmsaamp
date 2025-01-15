@extends('layouts.back')

@section('content')

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Liste des templates emails</h6>
    </div>
    <div class="card-body">

        <a href="{{ route('email-templates.create') }}" class="btn btn-primary mb-3">Créer un nouveau template</a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Sujet</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($templates as $template)
                <tr>
                    <td>{{ $template->name }}</td>
                    <td>{{ $template->subject }}</td>
                    <td>
                        <a href="{{ route('email-templates.edit', $template) }}" class="btn btn-warning btn-sm">Modifier</a>
                        <form action="{{ route('email-templates.destroy', $template) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection