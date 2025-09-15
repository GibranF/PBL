@php
    if (auth()->check()) {
        if (auth()->user()->usertype == 'admin') {
            $layout = 'layouts.admin';
        } elseif (auth()->user()->usertype == 'owner') {
            $layout = 'layouts.owner';
        } else {
            $layout = 'layouts.cust';
        }
    } else {
        $layout = 'layouts.cust';
    }
@endphp


@extends($layout)
@section('title', 'Profile')

@section('content')
    <div class="container-kotak">
        <h2 class="mb-4 font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Terjadi kesalahan:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Update Profile Information -->
            <div class="bg-white shadow sm:rounded-lg">
                <div class="p-6">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Update Password -->
            <div class="bg-white shadow sm:rounded-lg mt-6 md:mt-0">
                <div class="p-6">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>

        <!-- Delete User -->
        <div class="bg-white shadow sm:rounded-lg mt-6">
            <div class="p-6">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Tambahkan JavaScript jika diperlukan untuk form ini
        document.addEventListener('DOMContentLoaded', function () {
            // Contoh: Validasi atau logika tambahan
            console.log('Profile page loaded');
        });
    </script>
@endsection