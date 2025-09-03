@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <h1 class="page-title">Pengajuan Cuti</h1>

        @if(isset($remaining))
            <div class="mb-4">
                @if($remaining > 0)
                    <span class="chip-accent">Sisa Cuti: <strong id="sisaCuti">{{ $remaining }}</strong> hari</span>
                @else
                    <div class="field-error">Sisa cuti Anda: 0 hari — Anda tidak dapat mengajukan cuti lebih lanjut tahun ini. Hubungi HRD jika diperlukan.</div>
                @endif
            </div>
        @endif

    <form id="cutiForm" action="{{ route('pengajuan.cuti.store') }}" method="POST" enctype="multipart/form-data" class="form-card">
            @csrf
            {{-- Jenis cuti dihapus sesuai permintaan (field dihilangkan) --}}

            <div class="mb-4">
                <label class="form-label" for="tanggal_mulai">
                    Tanggal Mulai
                </label>
                <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-input" required>
            </div>

            <div class="mb-4">
                <label class="form-label" for="tanggal_selesai">
                    Tanggal Selesai
                </label>
                <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-input" required>
                <div id="quotaWarning" class="field-error" style="display:none; margin-top:0.5rem;"></div>
            </div>

            <div class="mb-4">
                <label class="form-label" for="alasan">
                    Alasan Cuti
                </label>
                <textarea name="alasan" id="alasan" rows="4" class="form-input" required minlength="10"></textarea>
                <p class="form-help">Minimal 10 huruf. Jelaskan alasan secara singkat namun jelas.</p>
                @error('alasan')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-6">
                <label class="form-label" for="dokumen_pendukung">
                    Dokumen Pendukung (opsional)
                </label>
                <input type="file" name="dokumen_pendukung" id="dokumen_pendukung" class="form-input">
                <p class="form-help">Format: PDF, JPG, JPEG, PNG (max. 2MB)</p>
            </div>

            <div class="flex items-center justify-between">
                <button id="submitBtn" type="submit" class="btn-primary">
                    Ajukan Cuti
                </button>
                <a href="{{ route('portal') }}" class="text-gray-600 hover:text-gray-800">
                    Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Calm colourful office style for Pengajuan Cuti page */
:root{
    --bg: #fbfdff;           /* very light */
    --card: #ffffff;
    --muted: #556170;        /* calm slate */
    --accent-1: #7dd3fc;     /* soft cyan */
    --accent-2: #c7b3ff;     /* lavender */
    --accent-3: #ffd6a5;     /* warm apricot */
    --ink: #0f172a;
    --primary: #2563eb;      /* deeper blue for CTA */
    --danger: #dc2626;
}
body { background: var(--bg); font-family: Inter, ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial; color: var(--ink); }
.form-card{
    background:var(--card);
    border-radius:14px;
    box-shadow:0 12px 30px rgba(15,23,42,0.06);
    padding:30px;
    border-left:6px solid transparent;
    background-image: linear-gradient(180deg, rgba(0,0,0,0.00), rgba(255,255,255,0));
    position:relative;
    overflow:hidden;
}
.form-card::before{
    content: '';
    position: absolute;
    left: 0; top: 0; bottom: 0;
    width: 6px;
    background: linear-gradient(180deg, var(--accent-2), var(--accent-1));
    opacity: 0.95;
}
.page-title{ font-size:1.4rem; font-weight:700; color:var(--ink); margin-bottom:0.5rem; }
.page-subtitle{ color:var(--muted); margin-bottom:1rem; }
.form-grid{ display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
.form-row{ margin-bottom:1rem; }
.form-label{ display:block; color:var(--ink); font-weight:600; margin-bottom:0.35rem; font-size:0.92rem; }
.form-label small{ color:var(--muted); font-weight:400; margin-left:0.5rem; font-size:0.8rem; }
.form-input{ width:100%; padding:0.7rem 0.95rem; border:1px solid rgba(86,100,120,0.08); border-radius:10px; background:#fff; color:var(--ink); font-size:0.95rem; transition:box-shadow .18s ease, border-color .18s ease, transform .06s ease; }
.form-input[type=file]{ padding:0.45rem; }
.form-input:focus{ outline:none; border-color: rgba(124, 58, 237, 0.32); box-shadow: 0 8px 30px rgba(124,58,237,0.08), 0 2px 6px rgba(34,211,238,0.04); transform:translateY(-1px); }
.form-help{ font-size:0.85rem; color:var(--muted); margin-top:0.35rem; }
.btn-primary{ background: linear-gradient(90deg, var(--accent-2), var(--accent-1)); color:var(--ink); padding:0.6rem 1.1rem; border-radius:10px; font-weight:700; border:none; box-shadow:0 10px 24px rgba(37,99,235,0.08); cursor:pointer; }
.btn-primary:hover{ transform:translateY(-2px); box-shadow:0 14px 34px rgba(37,99,235,0.12); }
.btn-primary:active{ transform:translateY(0); }
.btn-secondary{ color:var(--muted); text-decoration:underline; }

/* accent helper chip for date fields */
.chip-accent{ display:inline-block; padding:0.25rem 0.6rem; background: linear-gradient(90deg, rgba(124,58,237,0.08), rgba(34,211,238,0.06)); color:var(--muted); border-radius:999px; font-size:0.78rem; }

/* Full-width single column on small screens */
@media (max-width:768px){ .form-grid{ grid-template-columns: 1fr; } .form-card{ padding:20px; } }

/* Validation / status */
.field-error{ color:var(--danger); font-size:0.85rem; margin-top:0.35rem; }

/* subtle footer actions */
.form-actions{ display:flex; align-items:center; justify-content:space-between; gap:1rem; }

/* Loading overlay for submit */
.cuti-overlay{ position:fixed; inset:0; background:rgba(15,23,42,0.6); display:flex; align-items:center; justify-content:center; z-index:60; transition:opacity .2s ease; }
.cuti-overlay.hidden{ display:none; opacity:0; }
.cuti-overlay.visible{ display:flex; opacity:1; }
.cuti-overlay-inner{ background:rgba(255,255,255,0.98); padding:24px 28px; border-radius:12px; display:flex; flex-direction:column; align-items:center; gap:12px; box-shadow:0 12px 36px rgba(2,6,23,0.2); }
.cuti-overlay-inner img{ width:140px; height:auto; object-fit:contain; }
.cuti-overlay-text{ color:var(--ink); font-weight:700; font-size:1.05rem; text-align:center; }

</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('cutiForm');
    if (!form) return;
    var btn = document.getElementById('submitBtn');
    var overlay = null;
    // create overlay element
    function ensureOverlay(){
        if (overlay) return overlay;
        overlay = document.createElement('div');
        overlay.id = 'cuti-loading-overlay';
        overlay.className = 'cuti-overlay hidden';
        overlay.innerHTML = `
            <div class="cuti-overlay-inner">
                <img id="cuti-overlay-image" src="{{ asset('indosmart-update.png') }}" alt="Logo" />
                <p class="cuti-overlay-text">Menunggu persetujuan Dari HRD</p>
            </div>
        `;
        document.body.appendChild(overlay);
        return overlay;
    }

    var overlayTimeout = null;
    // remaining cuti passed from server
    var remaining = @json($remaining ?? null);
    var quotaWarningEl = document.getElementById('quotaWarning');
    var sisaCutiEl = document.getElementById('sisaCuti');

    function parseDateInput(id) {
        var v = document.getElementById(id).value;
        return v ? new Date(v) : null;
    }

    function computeRequestedDays() {
        var start = parseDateInput('tanggal_mulai');
        var end = parseDateInput('tanggal_selesai');
        if (!start || !end) return null;
        // compute inclusive days
        var diff = Math.floor((end - start) / (1000 * 60 * 60 * 24)) + 1;
        return diff;
    }

    function updateQuotaValidation() {
        if (remaining === null || remaining === undefined) {
            // no server info, skip custom quota validation
            if (quotaWarningEl) quotaWarningEl.style.display = 'none';
            if (sisaCutiEl) sisaCutiEl.textContent = '';
            return true;
        }
        if (sisaCutiEl) sisaCutiEl.textContent = remaining;
        if (remaining <= 0) {
            if (quotaWarningEl) {
                quotaWarningEl.style.display = 'block';
                quotaWarningEl.textContent = 'Sisa cuti Anda 0 hari — pengajuan diblokir.';
            }
            if (btn) btn.disabled = true;
            return false;
        }
        var requested = computeRequestedDays();
        if (requested === null) {
            if (quotaWarningEl) quotaWarningEl.style.display = 'none';
            if (btn) btn.disabled = false;
            return true;
        }
        if (requested > remaining) {
            if (quotaWarningEl) {
                quotaWarningEl.style.display = 'block';
                quotaWarningEl.textContent = 'Permintaan cuti (' + requested + ' hari) melebihi sisa cuti Anda (' + remaining + ' hari).';
            }
            if (btn) btn.disabled = true;
            return false;
        }
        if (quotaWarningEl) quotaWarningEl.style.display = 'none';
        if (btn) btn.disabled = false;
        return true;
    }

    function showOverlay() {
        ensureOverlay();
        overlay.classList.remove('hidden');
        overlay.classList.add('visible');
        if (btn) {
            btn.disabled = true;
            btn.innerText = 'Mengirim...';
            btn.classList.add('opacity-60', 'cursor-not-allowed');
        }
        // safety: if no response after 20s, hide overlay and re-enable button so user can retry
        if (overlayTimeout) clearTimeout(overlayTimeout);
        overlayTimeout = setTimeout(function () {
            hideOverlay();
            if (btn) {
                btn.disabled = false;
                btn.innerText = 'Ajukan Cuti';
                btn.classList.remove('opacity-60', 'cursor-not-allowed');
            }
            try { window.alert('Permintaan belum selesai. Silakan coba lagi atau periksa koneksi Anda.'); } catch (e) {}
        }, 20000);
    }

    function hideOverlay() {
        if (!overlay) return;
        overlay.classList.remove('visible');
        overlay.classList.add('hidden');
        if (overlayTimeout) { clearTimeout(overlayTimeout); overlayTimeout = null; }
    }

    form.addEventListener('submit', function (e) {
        // Only show overlay when form passes HTML5 validation
        try {
            if (typeof form.checkValidity === 'function' && !form.checkValidity()) {
                // let browser show built-in validation UI; ensure button stays enabled
                if (btn) {
                    btn.disabled = false;
                    btn.innerText = 'Ajukan Cuti';
                    btn.classList.remove('opacity-60', 'cursor-not-allowed');
                }
                return; // abort overlay display
            }
        } catch (err) {
            // If checkValidity isn't available for some reason, fall back to showing overlay
        }

        // custom quota validation: prevent overlay/submit if requested days exceed remaining
        if (!updateQuotaValidation()) {
            // prevent form submit
            e.preventDefault();
            try { window.alert('Permintaan cuti melebihi sisa cuti Anda. Silakan sesuaikan tanggal.'); } catch (e) {}
            return;
        }

        // show overlay and disable submit to avoid double submit
        showOverlay();
    });

    // If the page is shown again (navigation back/forward), ensure overlay/button state is reset
    window.addEventListener('pageshow', function () {
        hideOverlay();
        if (btn) {
            btn.disabled = false;
            btn.innerText = 'Ajukan Cuti';
            btn.classList.remove('opacity-60', 'cursor-not-allowed');
        }
    });

    // validate whenever dates change
    var startInput = document.getElementById('tanggal_mulai');
    var endInput = document.getElementById('tanggal_selesai');
    if (startInput) startInput.addEventListener('change', updateQuotaValidation);
    if (endInput) endInput.addEventListener('change', updateQuotaValidation);
    // initial check
    updateQuotaValidation();
});
</script>
@endpush
