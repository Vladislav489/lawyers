@extends('lawyers.layouts.main')
@section('title', 'Вход в систему')

@push('bootstrap')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        ul {
            margin: 0 !important;
        }
    </style>
@endpush

@section('content')
    <section class="mt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h1 class="fs-3">Вход в систему</h1>
                    <form
                        id="login-form"
                        class="mt-3 p-3 bg-primary-subtle"
                        action="http://lawyers/site/login"
                        method="post"
                        enctype="application/x-www-form-urlencoded"
                        style="border: 1px dashed"
                    >
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="phone">Номер телефона</label>
                            <input
                                id="phone"
                                class="form-control @error('phone_number') is-invalid @enderror"
                                type="tel"
                                name="phone_number"
                                value="{{ old('phone_number') }}"
                            >
                            @error('phone_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="password">Пароль</label>
                            <input
                                id="password"
                                class="form-control @error('password') is-invalid @enderror"
                                type="password"
                                name="password"
                                value="{{ old('password') }}"
                            >
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Войти</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
