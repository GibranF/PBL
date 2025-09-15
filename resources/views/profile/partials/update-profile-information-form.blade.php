{{-- Card Informasi Profil --}}
<div class="card shadow-sm border-0">
    <div class="card-body">
        <h5 class="card-title">Informasi Profil</h5>
        <p class="card-subtitle text-muted mb-3">Perbarui informasi akun anda.</p>
                        @if (session('status') === 'profile-updated')
                    <div class="text-success large">Perubahan disimpan.</div>
                @endif

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('patch')

            <div class="mb-3">
                <label for="name" class="form-label">Nama</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $user->name) }}"
                    required>
                @error('name')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control"
                    value="{{ old('email', $user->email) }}" required>
                @error('email')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <input type="text" id="alamat" name="alamat" class="form-control"
                    value="{{ old('alamat', $user->alamat) }}" required>
                @error('alamat')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="nomor_hp" class="form-label">Nomor HP</label>
                <input type="text" id="nomor_hp" name="nomor_hp" inputmode="numeric"
                    oninput="this.value = this.value.replace(/[^0-9]/g, '')" maxlength="15" class="form-control"
                    value="{{ old('nomor_hp', $user->nomor_hp) }}" required>
                @error('nomor_hp')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
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