@extends('layouts.app')

@section('content')
<div class="container py-2">
    <div class="row g-4">
        <div class="col-lg-8">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th scope="col">Kod</th>
                        <th scope="col">Typ / Wartość</th>
                        <th scope="col">Limity</th>
                        <th scope="col">Ważny</th>
                        <th scope="col">Kategoria</th>
                        <th scope="col">Status</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($coupons as $coupon)
                    <tr class="{{ !$coupon->is_active ? 'table-light text-muted' : '' }}">
                        <td class="fw-bold">{{ $coupon->code }}</td>
                        <td>
                            <small
                                class="d-block text-muted">{{ $coupon->type == 'fixed' ? 'Stały' : 'Procentowy' }}</small>
                            {{ number_format($coupon->value, 2) }} {{ $coupon->type == 'fixed' ? 'zł' : '%' }}
                        </td>
                        <td>
                            <small class="d-block">Użyto: {{ $coupon->used_count }} /
                                {{ $coupon->usage_limit ?? '∞' }}</small>
                            @if($coupon->min_cart_value) <small>Min: {{ $coupon->min_cart_value }} zł</small> @endif
                        </td>
                        <td>
                            <small class="d-block">Od: {{ $coupon->starts_at }}</small>
                            <small class="d-block">Do: {{ $coupon->expires_at }}</small>
                        </td>
                        <td>
                            <small class="d-block">{{ $coupon->category->name ?? '' }}</small>
                        </td>
                        <td>
                            <span class="badge {{ $coupon->is_active ? 'bg-success' : 'bg-secondary' }}">
                                {{ $coupon->is_active ? 'Aktywny' : 'Wyłączony' }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-2">
                                <form action="{{ route('coupons.resetUsage', $coupon->id) }}" method="POST" class="me-2"
                                    onsubmit="return confirm('Czy na pewno chcesz wyzerować licznik dla tego kuponu?');">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-secondary border-0"
                                        title="Wyczyść licznik użyć">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-arrow-counterclockwise"
                                            viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                d="M8 3a5 5 0 1 1-4.546 2.914.5.5 0 0 0-.908-.417A6 6 0 1 0 8 2v1z" />
                                            <path
                                                d="M8 4.466V.534a.25.25 0 0 0-.41-.192L5.23 2.308a.25.25 0 0 0 0 .384l2.36 1.966A.25.25 0 0 0 8 4.466z" />
                                        </svg>
                                    </button>
                                </form>

                                <button class="btn btn-sm btn-outline-primary border-0"
                                    onclick='editCoupon(@json($coupon))'>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor"
                                        class="bi bi-pencil-square" viewBox="0 0 16 16">
                                        <path
                                            d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                        <path fill-rule="evenodd"
                                            d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                    </svg>
                                </button>

                                <form action="{{ route('coupons.destroy', $coupon->id) }}" method="POST"
                                    onsubmit="return confirm('Czy na pewno chcesz usunąć ten kod?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger border-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
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
                </tbody>
            </table>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 sticky-top" style="border-radius: 15px; top: 20px;">
                <div class="card-body p-4">
                    <h5 id="formTitle" class="fw-bold mb-3">Dodaj kod rabatowy</h5>

                    <form id="couponForm" action="{{ route('coupons.store') }}" method="POST">
                        @csrf
                        <div id="methodField"></div>

                        <div class="row g-2 mb-3">
                            <div class="col-md-12">
                                <label class="form-label small fw-bold text-uppercase">Kod</label>
                                <input type="text" name="code" id="couponCode" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-uppercase">Typ</label>
                                <select name="type" id="couponType" class="form-select">
                                    <option value="fixed">Stały (zł)</option>
                                    <option value="percent">Procent (%)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-uppercase">Wartość</label>
                                <input type="number" step="0.01" name="value" id="couponValue" class="form-control"
                                    required>
                            </div>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-uppercase">Min. kwota koszyka
                                (opcjonalnie)</label>
                            <input type="number" step="0.01" name="min_cart_value" id="minCartValue"
                                class="form-control">
                        </div>


                        <div class="mb-3">
                            <label for="categoryId" class="form-label small fw-bold text-secondary text-uppercase"
                                style="font-size: 0.7rem;">
                                Ogranicz do kategorii (opcjonalnie)
                            </label>

                            <select name="category_id" id="categoryId" class="form-select">
                                <option value="">--- WSZYSTKIE PRODUKTY ---</option>

                                @foreach($categories as $mainCat)
                                <option value="{{ $mainCat->id }}" disabled
                                    style="font-weight: bold; background-color: #eee;">
                                    {{ $mainCat->name }} (Główna)
                                </option>

                                @foreach($mainCat->children as $subCat)
                                <option value="{{ $subCat->id }}" disabled
                                    style="padding-left: 20px; font-style: italic;">
                                    -- {{ $subCat->name }}
                                </option>

                                @foreach($subCat->children as $leafCat)
                                <option value="{{ $leafCat->id }}"
                                    {{ (isset($coupon) && $coupon->category_id == $leafCat->id) ? 'selected' : '' }}
                                    style="padding-left: 40px;">
                                    &nbsp;&nbsp;&nbsp;&nbsp;↳ {{ $leafCat->name }}
                                </option>
                                @endforeach
                                @endforeach
                                @endforeach
                            </select>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-uppercase">Ważny od</label>
                                <input type="datetime-local" name="starts_at" id="startsAt" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-uppercase">Ważny do</label>
                                <input type="datetime-local" name="expires_at" id="expiresAt" class="form-control">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-uppercase">Limit użyć (puste = bez
                                limitu)</label>
                            <input type="number" name="usage_limit" id="usageLimit" class="form-control">
                        </div>

                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="isActive" value="1"
                                    checked>
                                <label class="form-check-label fw-bold" for="isActive">Kod aktywny</label>
                            </div>
                        </div>

                        <button type="submit" id="submitBtn" class="btn btn-primary w-100 py-2 fw-bold shadow">Zapisz
                            kod</button>
                        <button type="button" id="cancelBtn" class="btn btn-link w-100 text-muted mt-2 d-none"
                            onclick="resetForm()">Anuluj i wróć do dodawania</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function editCoupon(coupon) {
    console.log(coupon); // Dla debugowania w konsoli

    document.getElementById('formTitle').innerText = 'Edycja kodu';
    document.getElementById('submitBtn').innerText = 'Aktualizuj kod';
    document.getElementById('submitBtn').classList.replace('btn-primary', 'btn-warning');
    document.getElementById('cancelBtn').classList.remove('d-none');

    const form = document.getElementById('couponForm');
    form.action = `/coupons/${coupon.id}`;
    document.getElementById('methodField').innerHTML = '@method("PUT")';

    // Mapowanie pól - upewnij się, że ID pasują do nazw w HTML
    document.getElementById('couponCode').value = coupon.code || '';
    document.getElementById('couponType').value = coupon.type || 'fixed';
    document.getElementById('couponValue').value = coupon.value || '';
    document.getElementById('minCartValue').value = coupon.min_cart_value || '';
    document.getElementById('usageLimit').value = coupon.usage_limit || '';

    // Status (checkbox)
    document.getElementById('isActive').checked = parseInt(coupon.is_active) === 1;

    // Obsługa dat (konwersja formatu bazy danych na datetime-local)
    if (coupon.starts_at) {
        document.getElementById('startsAt').value = coupon.starts_at.slice(0, 16).replace(' ', 'T');
    } else {
        document.getElementById('startsAt').value = '';
    }

    if (coupon.expires_at) {
        document.getElementById('expiresAt').value = coupon.expires_at.slice(0, 16).replace(' ', 'T');
    } else {
        document.getElementById('expiresAt').value = '';
    }

    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

function resetForm() {
    document.getElementById('formTitle').innerText = 'Dodaj kod rabatowy';
    document.getElementById('submitBtn').innerText = 'Zapisz kod';
    document.getElementById('submitBtn').classList.replace('btn-warning', 'btn-primary');
    document.getElementById('cancelBtn').classList.add('d-none');
    document.getElementById('methodField').innerHTML = '';
    document.getElementById('couponForm').action = "{{ route('coupons.store') }}";
    document.getElementById('couponForm').reset();
}
</script>
@endsection