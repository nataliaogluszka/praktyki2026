@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row g-4">
        {{-- LEWA KOLUMNA: TABELA --}}
        <div class="col-lg-8">
            <div class="card shadow-sm border-0" style="border-radius: 15px;">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-primary"><i class="fas fa-layer-group me-2"></i>Struktura Kategorii
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4" style="width: 80px;">ID</th>
                                    <th>Nazwa i Hierarchia</th>
                                    <th>Slug</th>
                                    <th class="text-end pe-4">Akcje</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $mainCat)
                                {{-- POZIOM 1 --}}
                                <tr class="table-sm border-bottom-0">
                                    <td class="ps-4 text-muted small">#{{ $mainCat->id }}</td>
                                    <td>
                                        <span class="badge bg-primary px-3 py-2"
                                            style="font-size: 0.85rem; letter-spacing: 0.5px;">
                                            <i class="fas fa-folder me-2"></i>{{ $mainCat->name }}
                                        </span>
                                    </td>
                                    <td><code class="text-primary small">{{ $mainCat->slug }}</code></td>
                                    <td class="text-end pe-4">
                                        <div class="d-flex align-items-center justify-content-end gap-2">
                                            <button type="button" onclick="editCategory('{{ $mainCat->id }}', '{{ $mainCat->name }}', '{{$mainCat->slug }}', '{{ $mainCat->parent_id }}')" class="btn btn-sm btn-outline-secondary border-0">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                    fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                    <path
                                                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                                    <path fill-rule="evenodd"
                                                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                                </svg>
                                            </button>
                                            <form action="{{ route('categories.destroy', $mainCat->id) }}" method="POST"
                                                class="d-inline" onsubmit="return confirm('Czy na pewno?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger border-0">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                                        <path
                                                            d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                                                        <path
                                                            d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                {{-- POZIOM 2 --}}
                                @foreach($mainCat->children as $subCat)
                                <tr class="border-bottom-0">
                                    <td class="ps-4 text-muted small">{{ $subCat->id }}</td>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="border-start border-bottom ms-2 me-2"
                                                style="width: 20px; height: 15px; margin-top: -10px; border-color: #cbd5e0 !important;">
                                            </div>
                                            <span class="fw-semibold text-dark"><i
                                                    class="far fa-folder-open text-warning me-1"></i>
                                                {{ $subCat->name }}</span>
                                        </div>
                                    </td>
                                    <td><small class="text-muted">{{ $subCat->slug }}</small></td>
                                    <td class="text-end pe-4">
                                        <div class="d-flex align-items-center justify-content-end gap-2">
                                            <button type="button"
                                                onclick="editCategory('{{ $subCat->id }}', '{{ $subCat->name }}', '{{ $subCat->slug }}', '{{ $subCat->parent_id }}')"
                                                class="btn btn-sm btn-outline-secondary border-0">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                    fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                    <path
                                                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                                    <path fill-rule="evenodd"
                                                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                                </svg>
                                            </button>
                                            <form action="{{ route('categories.destroy', $subCat->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger border-0">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                                        <path
                                                            d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                                                        <path
                                                            d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                {{-- POZIOM 3 --}}
                                @foreach($subCat->children as $leafCat)
                                <tr>
                                    <td class="ps-4 text-muted small">{{ $leafCat->id }}</td>
                                    <td class="ps-5">
                                        <div class="d-flex align-items-center ms-3">
                                            <div class="border-start border-bottom me-2"
                                                style="width: 15px; height: 15px; margin-top: -10px; border-color: #e2e8f0 !important;">
                                            </div>
                                            <span class="text-muted small"><i class="fas fa-tag me-1"></i>
                                                {{ $leafCat->name }}</span>
                                        </div>
                                    </td>
                                    <td><small class="text-muted italic"
                                            style="font-size: 0.75rem;">{{ $leafCat->slug }}</small></td>
                                    <td class="text-end pe-4">
                                        <div class="d-flex align-items-center justify-content-end gap-2">
                                            <button type="button"
                                                onclick="editCategory('{{ $leafCat->id }}', '{{ $leafCat->name }}', '{{ $leafCat->slug }}', '{{ $leafCat->parent_id }}')"
                                                class="btn btn-sm btn-outline-secondary border-0">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                    fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                    <path
                                                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                                    <path fill-rule="evenodd"
                                                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                                </svg>
                                            </button>
                                            <form action="{{ route('categories.destroy', $leafCat->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger border-0">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                        fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                                        <path
                                                            d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                                                        <path
                                                            d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- PRAWA KOLUMNA: FORMULARZ --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 sticky-top" style="border-radius: 15px; top: 20px; z-index: 1000;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 id="formTitle" class="fw-bold mb-0 text-dark">Dodaj kategorię</h5>
                        <span id="editBadge" class="badge rounded-pill bg-warning text-dark d-none">Edycja</span>
                    </div>

                    <form id="categoryForm" action="{{ route('categories.store') }}" method="POST">
                        @csrf
                        <div id="methodField"></div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary text-uppercase"
                                style="font-size: 0.7rem;">Nazwa</label>
                            <input type="text" name="name" id="catName"
                                class="form-control border-light shadow-none bg-light" placeholder="Wpisz nazwę..."
                                required onkeyup="generateSlug(this.value)">
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary text-uppercase"
                                style="font-size: 0.7rem;">Slug (URL)</label>
                            <input type="text" name="slug" id="catSlug"
                                class="form-control border-light shadow-none bg-light" placeholder="np. buty-biegowe"
                                required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold text-secondary text-uppercase"
                                style="font-size: 0.7rem;">Rodzic</label>
                            <select name="parent_id" id="catParent"
                                class="form-select border-light shadow-none bg-light">
                                <option value="">--- KATEGORIA GŁÓWNA ---</option>
                                @foreach($categories as $mCat)
                                <option value="{{ $mCat->id }}" class="fw-bold">{{ $mCat->name }}</option>
                                @foreach($mCat->children as $sCat)
                                <option value="{{ $sCat->id }}">&nbsp;&nbsp;&nbsp;↳ {{ $sCat->name }}</option>
                                @endforeach
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" id="submitBtn"
                            class="btn btn-primary w-100 py-2 fw-bold rounded-3 shadow">
                            <i class="fas fa-plus me-2"></i> Zapisz
                        </button>

                        <button type="button" id="cancelBtn"
                            class="btn btn-link w-100 text-muted mt-3 d-none text-decoration-none"
                            onclick="resetForm()">
                            Anuluj i wróć do dodawania
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Automatyczne generowanie Sluga
function generateSlug(text) {
    if (document.getElementById('editBadge').classList.contains('d-none')) {
        const slug = text.toLowerCase()
            .replace(/[^\w ]+/g, '')
            .replace(/ +/g, '-');
        document.getElementById('catSlug').value = slug;
    }
}

// Przełączanie w tryb edycji
function editCategory(id, name, slug, parentId) {
    document.getElementById('formTitle').innerText = 'Edycja kategorii';
    document.getElementById('submitBtn').classList.replace('btn-primary', 'btn-warning');
    document.getElementById('submitBtn').innerHTML = '<i class="fas fa-sync me-2"></i> Aktualizuj';
    document.getElementById('editBadge').classList.remove('d-none');
    document.getElementById('cancelBtn').classList.remove('d-none');

    const form = document.getElementById('categoryForm');
    form.action = `/categories/${id}`; // Upewnij się, że URL pasuje do route('categories.update')
    document.getElementById('methodField').innerHTML = '@method("PUT")';

    document.getElementById('catName').value = name;
    document.getElementById('catSlug').value = slug;
    document.getElementById('catParent').value = parentId || "";

    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

// Powrót do dodawania
function resetForm() {
    document.getElementById('formTitle').innerText = 'Dodaj kategorię';
    document.getElementById('submitBtn').classList.replace('btn-warning', 'btn-primary');
    document.getElementById('submitBtn').innerHTML = '<i class="fas fa-plus me-2"></i> Zapisz';
    document.getElementById('editBadge').classList.add('d-none');
    document.getElementById('cancelBtn').classList.add('d-none');

    const form = document.getElementById('categoryForm');
    form.action = "{{ route('categories.store') }}";
    document.getElementById('methodField').innerHTML = '';
    form.reset();
}
</script>

<style>
.bg-soft-primary {
    background-color: #ebf4ff;
    color: #3182ce;
}

.table td {
    border-color: #f8fafc;
}

.btn-group .btn {
    padding: 0.25rem 0.6rem;
}

.sticky-top {
    transition: all 0.3s ease;
}

input:focus,
select:focus {
    border-color: #a3bffa !important;
    box-shadow: 0 0 0 3px rgba(163, 191, 250, 0.2) !important;
}
</style>
@endsection