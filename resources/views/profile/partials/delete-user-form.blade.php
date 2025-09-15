{{-- Card Hapus Akun --}}
<div class="col-md-12">
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h5 class="card-title text-danger">Hapus Akun</h5>
            <p class="card-subtitle text-muted mb-3">
                Sekali akun dihapus, semua data akan hilang. Pastikan keputusanmu sudah bulat.
            </p>

            <button class="btn btn-danger" x-data x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">
                Hapus Akun
            </button>

            <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
                <form method="POST" action="{{ route('profile.destroy') }}" class="p-4">
                    @csrf
                    @method('delete')

                    <h5 class="mb-3">Konfirmasi Hapus Akun</h5>
                    <p>Masukkan password anda untuk melanjutkan proses penghapusan akun.</p>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input id="password" name="password" type="password" class="form-control" required>
                        @error('password', 'userDeletion')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary" x-on:click="$dispatch('close')">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-danger">
                            Hapus Akun
                        </button>
                    </div>
                </form>
            </x-modal>
        </div>
    </div>
</div>
</div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Sembunyikan pesan sukses setelah 2 detik
        const successMessage = document.getElementById('success-message');
        if (successMessage) {
            setTimeout(() => {
                successMessage.style.display = 'none';
            }, 2000);
        }
    });
</script>