<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('storage/favicon.svg') }}" type="image/x-icon">
    <title>{{ "Dashboard" ?? '' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment/moment.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>

    <style>
        .navbar {
            transition: all 0.3s ease;
        }

        .navbar-light .navbar-nav .nav-link {
            font-size: 16px;
            font-weight: 500;
            color: #555;
        }

        .navbar-light .nav-link:hover,
        .navbar-light .nav-link.active {
            color: #007bff;
        }

        .active {
            color: #007bff;
        }

        .navbar-light .navbar-toggler-icon {
            background-color: #007bff;
        }

        .navbar-brand {
            font-weight: bold;
        }

        .navbar-brand:hover {
            color: #000;
        }

        .navbar-nav .nav-item {
            margin-left: 20px;
        }

        .nav-item.dropdown:hover>.dropdown-menu {
            display: block;
        }

        .dropdown-menu {
            border-radius: 10px;
            border: 1px solid #ddd;
            top: 38px !important;
        }

        .navbar-light .navbar-nav .nav-link {
            transition: color 0.3s;
        }

        .logincard {
            box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
            padding: 30px;
            border-radius: 10px;
            width: 500px;
            background-color: #fff;
            z-index: 1;
        }

        .loginmain {
            position: relative;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: url({{ url('build/image/loginback.jpg') }});
            background-repeat: no-repeat;
            background-size: cover;
            overflow: hidden;
        }

        .loginmain::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgb(0 0 0 / 63%);
            z-index: 1;
        }

        .login-header {
            font-size: 20px;
            font-weight: 600;
            text-align: center;
        }

        .eye_posiotin {
            position: relative;
        }

        .eye_posiotin span {
            position: absolute;
            top: 50%;
            right: 5px;
            transform: translate(-50%, -50%)
        }


        @media (max-width: 768px) {
            .navbar-nav {
                text-align: center;
                margin-top: 15px;
            }

            .nav-item {
                margin-left: 0;
                margin-top: 10px;
            }

            .navbar-text {
                text-align: center;
                margin-top: 15px;
            }

            .dropdown-menu {
                text-align: center;

            }

        }
    </style>
</head>

<body>
    <div class="container-fluid p-0">
        @if (!request()->is('login'))
            <nav class="navbar navbar-expand-lg navbar-light bg-light ps-4 pe-4 shadow-sm">
                <div class="container">
                    <a class="navbar-brand" href="/dashboard">AI Client</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false"
                        aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-between" id="navbarContent">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}"
                                    href="/dashboard">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('history') ? 'active' : '' }}" href="/history">
                                    Position-History</a>
                            </li>
                        </ul>

                        <div class="navbar-text">
                            @guest
                            @else
                                <div class="dropdown">
                                    <span
                                        class="rounded-circle bg-secondary text-white d-flex justify-content-center align-items-center"
                                        style="width: 40px; height: 40px; font-size: 18px; font-weight: bold; cursor: pointer;"
                                        id="dropdownUser" data-bs-toggle="dropdown">
                                        {{ strtoupper(Str::substr(Auth::user()->client_name, 0, 1)) }}
                                    </span>
                                    <ul class="dropdown-menu dropdown-menu-end p-3 shadow-lg border-0 mt-2"
                                        aria-labelledby="dropdownUser">
                                        <div class="d-flex align-items-center mb-2">
                                            <span
                                                class="rounded-circle bg-secondary text-white d-flex justify-content-center align-items-center"
                                                style="width: 45px; height: 45px; font-size: 20px; font-weight: bold;">
                                                {{ strtoupper(Str::substr(Auth::user()->client_name, 0, 1)) }}
                                            </span>

                                            <div class="ms-3">
                                                <span
                                                    class="d-block fw-bold text-dark">{{ Auth::user()->client_name }}</span>
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-center text-muted small py-2 border-top">
                                            <i class="fa-regular fa-envelope me-2 text-secondary"></i>
                                            <span>{{ Auth::user()->client_email }}</span>
                                        </div>


                                        <livewire:logout />
                                    </ul>
                                </div>
                            @endguest
                        </div>
                    </div>
                </div>
            </nav>
        @endif
        <div class="@if (!request()->is('login')) container @endif">
            {{ $slot }}
        </div>
    </div>
</body>

</html>
