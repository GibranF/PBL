{{-- Card Hapus Akun --}}
<div class="col-md-12">
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h5 class="card-title text-danger">Hapus Akun</h5>
            <p class="card-subtitle text-muted mb-3">
                Sekali akun dihapus, semua data akan hilang. Pastikan keputusanmu sudah bulat.
            </p>

            {{-- Form konfirmasi langsung --}}
            <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirmDelete()">
                @csrf
                @method('DELETE')

                <div class="mb-3">
                    <label for="password" class="form-label">Masukkan Password</label>
                    <input id="password" name="password" type="password" class="form-control" required>
                    @error('password', 'userDeletion')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-danger w-100">
                    Hapus Akun
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function confirmDelete() {
        return confirm('Apakah Anda yakin ingin menghapus akun ini? Semua data akan hilang.');
        }
</script>
