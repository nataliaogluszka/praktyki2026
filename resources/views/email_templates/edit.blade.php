@extends('layouts.app')

@section('content')
<div class="container pb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Edycja szablonu: {{ $emailTemplate->name }}</h2>
        <a href="{{ route('settings.index') }}" class="btn btn-outline-secondary rounded-pill">Powrót</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('email-templates.update', $emailTemplate->id) }}" method="POST">
        @csrf
        @method('PATCH')

        <div class="row">
            <div class="col-md-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Temat wiadomości</label>
                            <input type="text" name="subject" value="{{ $emailTemplate->subject }}" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Treść e-maila</label>
                            <textarea name="content" class="email-editor">{{ $emailTemplate->content }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Podpowiedzi zmiennych</h5>
                        <p class="small text-muted">Możesz używać tych znaczników w treści i temacie:</p>
                        <ul class="list-group list-group-flush small">
                            <li class="list-group-item"><code>@{{ $customer_name }}</code> - Imię i nazwisko</li>
                            <li class="list-group-item"><code>@{{ $order_number }}</code> - Numer zamówienia</li>
                            <li class="list-group-item"><code>@{{ $total_amount }}</code> - Suma zamówienia</li>
                        </ul>
                        <hr>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Wewnętrzna nazwa</label>
                            <input type="text" name="name" value="{{ $emailTemplate->name }}" class="form-control form-control-sm">
                        </div>
                        <button type="submit" class="btn btn-primary w-100 rounded-pill shadow">Zapisz zmiany</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js"></script>
<script>
  tinymce.init({
    selector: 'textarea.email-editor',
    height: 500,
    plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
    toolbar: 'undo redo | blocks | bold italic underline | link image table | align lineheight | numlist bullist | removeformat',
    branding: false,
    promotion: false
  });
</script>
@endsection