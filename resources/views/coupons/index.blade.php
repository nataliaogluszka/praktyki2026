@extends('layouts.app')

@section('content')
<div class="container py-2">
    <div class="row g-4">
        <div class="col-lg-8">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col" class="col-md-3">Kod</th>
                        <th scope="col" class="col-md-2">Typ</th>
                        <th scope="col" class="col-md-2">Wartość</th>
                        <th scope="col" class="col-md-1">Akcje</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($coupons as $coupon)
                    <tr>
                        <td class="fw-bold">{{ $coupon->code }}</td>
                        <td>{{ $coupon->type == 'fixed' ? 'Stały' : 'Procentowy' }}</td>
                        <td>{{ number_format($coupon->value, 2) }} {{ $coupon->type == 'fixed' ? 'zł' : '%' }}</td>
                        <td>
                            <div class="d-flex gap-2 align-items-center">
                                <button class="btn btn-sm btn-outline-primary border-0" 
                                    onclick="editCoupon('{{ $coupon->id }}', '{{ $coupon->code }}', '{{ $coupon->type }}', '{{ $coupon->value }}')">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                    fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
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
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                                            <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
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
            <div class="card shadow-sm border-0 sticky-top" style="border-radius: 15px; top: 20px; z-index: 1000;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 id="formTitle" class="fw-bold mb-0 text-dark">Dodaj kod rabatowy</h5>
                        <span id="editBadge" class="badge rounded-pill bg-warning text-dark d-none">Edycja</span>
                    </div>

                    <form id="couponForm" action="{{ route('coupons.store') }}" method="POST">
                        @csrf
                        <div id="methodField"></div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary text-uppercase" style="font-size: 0.7rem;">Kod (np. LATO2024)</label>
                            <input type="text" name="code" id="couponCode" class="form-control border-light bg-light" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary text-uppercase" style="font-size: 0.7rem;">Typ rabatu</label>
                            <select name="type" id="couponType" class="form-select border-light bg-light" required>
                                <option value="fixed">Kwotowy (zł)</option>
                                <option value="percent">Procentowy (%)</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold text-secondary text-uppercase" style="font-size: 0.7rem;">Wartość</label>
                            <input type="number" step="0.01" name="value" id="couponValue" class="form-control border-light bg-light" placeholder="0.00" required>
                        </div>

                        <button type="submit" id="submitBtn" class="btn btn-primary w-100 py-2 fw-bold rounded-3 shadow">
                            Zapisz kod
                        </button>

                        <button type="button" id="cancelBtn" class="btn btn-link w-100 text-muted mt-3 d-none text-decoration-none" onclick="resetForm()">
                            Anuluj i wróć do dodawania
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function editCoupon(id, code, type, value) {
    document.getElementById('formTitle').innerText = 'Edycja kodu';
    document.getElementById('submitBtn').classList.replace('btn-primary', 'btn-warning');
    document.getElementById('submitBtn').innerText = 'Aktualizuj kod';
    document.getElementById('editBadge').classList.remove('d-none');
    document.getElementById('cancelBtn').classList.remove('d-none');

    const form = document.getElementById('couponForm');
    form.action = `/coupons/${id}`; 
    document.getElementById('methodField').innerHTML = '@method("PUT")';

    document.getElementById('couponCode').value = code;
    document.getElementById('couponType').value = type;
    document.getElementById('couponValue').value = value;

    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function resetForm() {
    document.getElementById('formTitle').innerText = 'Dodaj kod rabatowy';
    document.getElementById('submitBtn').classList.replace('btn-warning', 'btn-primary');
    document.getElementById('submitBtn').innerText = 'Zapisz kod';
    document.getElementById('editBadge').classList.add('d-none');
    document.getElementById('cancelBtn').classList.add('d-none');

    const form = document.getElementById('couponForm');
    form.action = "{{ route('coupons.store') }}";
    document.getElementById('methodField').innerHTML = '';
    form.reset();
}
</script>
@endsection