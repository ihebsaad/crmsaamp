@extends('layouts.app')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">{{ isset($emailTemplate) ? 'Mettre à jour' : 'Créer' }} template email</h6>
    </div>
    <div class="card-body">
        <h1>{{ isset($emailTemplate) ? 'Modifier le template' : 'Créer un nouveau template' }}</h1>
        <form action="{{ isset($emailTemplate) ? route('email-templates.update', $emailTemplate) : route('email-templates.store') }}" method="POST">
            @csrf
            @isset($emailTemplate)
            @method('PUT')
            @endisset

            <div class="mb-3">
                <label for="name" class="form-label">Nom</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $emailTemplate->name ?? '') }}" required>
            </div>

            <div class="mb-3">
                <label for="subject" class="form-label">Sujet</label>
                <input type="text" name="subject" id="subject" class="form-control" value="{{ old('subject', $emailTemplate->subject ?? '') }}" required>
            </div>

            <div class="mb-3">
                <label for="body" class="form-label">Corps</label>
                <textarea name="body" id="body" class="form-control" rows="5" required>{{ old('body', $emailTemplate->body ?? '') }}</textarea>
            </div>

            <button type="submit" class="btn btn-success">{{ isset($emailTemplate) ? 'Mettre à jour' : 'Créer' }}</button>
        </form>
    </div>
</div>
@endsection