@extends('layouts.admin.guest')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    @if(session()->has('message'))
                        <span class = "text-red-500">{{ session()->get('message') }}</span>
                    @endif
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.login') }}">
                            @csrf

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('電子郵件') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror rounded-lg w-full" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mt-4">
                                <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('密碼') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror rounded-lg w-full" name="password" required autocomplete="current-password">

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mt-4">
                                <div class="col-md-6 offset-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                        <label class="form-check-label" for="remember">
                                            {{ __('記得我') }}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-0 mt-4">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary bg-gray-500 rounded-lg w-20 h-10 text-white">
                                        {{ __('登入') }}
                                    </button>

                                    @if (Route::has('password.request'))
                                        <a class="btn btn-link ml-4" href="{{ route('password.request') }}">
                                            {{ __('忘記密碼?') }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection