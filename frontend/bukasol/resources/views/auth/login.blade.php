<!-- resources/views/livewire/login.blade.php -->
@extends('layouts.app')

@section('title', 'Login') <!-- Optional title section -->

@push('styles')
    <style>
        body {
            background-color: #527900;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100dvh;
            margin: 0;
            min-height: 100dvh;
        }

        #LoginPage {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100dvh;
            margin: 0;
            min-height: 100dvh;
        }

        .login-box {
            width: 100%;
            min-width: 300px;
            max-width: 400px;
            background-color: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
        }

        .logo-container img {
            width: 80px;
            margin-bottom: 20px;
        }

        h2 {
            padding: 10px;
            border-radius: 8px;
            color: #000000;
            margin-bottom: 25px;
            font-size: 16px;
        }

        .form-label {
            color: #000000;
            text-align: left;
        }

        .form-control {
            background-color: #ffffff;
            border: 1px solid #6c757d;
            color: #000000;
        }

        .form-control::placeholder {
            color: #aaaaaa;
            opacity: 1;
        }

        .form-control:focus {
            border-color: #6236FF;
            box-shadow: none;
        }

        .form-check-label {
            color: #fff;
        }

        .forgot-password {
            color: #A6A6A6;
            font-size: 12px;
            display: block;
            text-decoration: none;
            margin-top: 10px;
        }

        .forgot-password:hover {
            color: #E4E4E7;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #842029;
            border: 1px solid #f5c2c7;
            border-radius: 4px;
            padding: 10px;
            margin-bottom: 15px;
            text-align: center;
        }

        #LoginLogo {
            border-radius: 50%;
            margin: 3%;
            width: 150px;
            height: 150px;
            object-fit: cover;
        }

        @media (max-width: 576px) {
            .login-box {
                padding: 15px;
            }

            .logo-container img {
                width: 60px;
            }

            h2 {
                font-size: 14px;
            }

            .form-control {
                font-size: 14px;
            }

            .forgot-password {
                font-size: 10px;
            }

            #LoginLogo {
                width: 100px;
                height: 100px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid" id="LoginPage" style="align-content: center">
        <div class="container-fluid align-items-center">
            <div class="align-items-center align-content-center d-flex justify-content-center container-fluid">
                <div class="login-box">

                    <div class="logo-container container-fluid text-center px-0 py-0">
                        <img id="LoginLogo" src="{{ asset('Logo.png') }}" alt="Logo">
                    </div>

                    <h2 class="text-center rounded-1 pt-0 mt-2 fw-bold">Buku Anak Soleh Digital</h2>

                    <!-- Error message display -->
                    @if (session('error'))
                        <div class="alert-error">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <input class="form-control border border-4 rounded-3" id="username" name="username" type="text" placeholder="Username" required autocomplete="">
                        </div>
                        <div class="mb-3">
                            <input class="form-control border border-4 rounded-3" id="password" name="password" type="password" placeholder="Password" required autocomplete="">
                        </div>
                        <div class="d-flex justify-content-between button-group gap-2 pt-2 px-0 py-0 mx-0 my-0">
                            <button class="container-fluid btn btn-secondary fw-medium" type="submit">Login</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
