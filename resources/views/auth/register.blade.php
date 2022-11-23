<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>ePLDT | Registration</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="{{ asset('assets/img/epldt-suite-logo.png') }}" rel="icon">
    <link href="{{ asset('assets/img/epldt-suite-logo.png') }}" rel="apple-touch-icon">

    <link rel="stylesheet" href="{{ asset('assets/css/luno-style.css') }}">
    <script src="{{ asset('assets/js/plugins.js') }}"></script>

</head>

<body>

    <main>
        <div class="container">

            <section
                class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

                            <div class="d-flex justify-content-center py-4">
                                <a href="#" class="logo d-flex align-items-center w-auto">
                                    <img src="{{ asset('assets/img/epldt-suite-logo.png') }}" height="50" width="50" alt="">
                                    <h4 class="px-2 font-weight-bolder d-none d-lg-block">ePLDT</h4>
                                </a>
                            </div>

                            <div class="card mb-3">

                                <div class="card-body">

                                    <div class="pb-2">
                                        <h5 class="card-title text-center pb-0 fs-4">Create an Account</h5>
                                        <p class="text-center small">Enter your personal details to create account</p>
                                    </div>

                                    <form action="{{ route('auth.register') }}" method="POST" class="row g-3 needs-validation">
                                        
                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif                                   

                                        @csrf
                                        <div class="col-12">
                                            <label for="FirstName" class="form-label">First Name</label>
                                            <input type="text" name="FirstName" class="form-control" id="FirstName"
                                                value="{{ old('FirstName') }}"
                                                required>
                                            <span class="text-danger">@error('FirstName'){{ $message }}@enderror</span>
                                        </div>

                                        <div class="col-12">
                                            <label for="LastName" class="form-label">Last Name</label>
                                            <input type="text" name="LastName" class="form-control" id="LastName"
                                                value="{{ old('LastName') }}"
                                                required>
                                            <span class="text-danger">@error('LastName'){{ $message }}@enderror</span>
                                        </div>                                        

                                        <div class="col-12">
                                            <label for="email" class="form-label">Your Email</label>
                                            <input type="email" name="email" class="form-control" id="email"
                                                value="{{ old('email') }}"
                                                required>
                                            <span class="text-danger">@error('email'){{ $message }}@enderror</span>
                                        </div>

                                        <div class="col-12">
                                            <label for="password" class="form-label">Password</label>
                                            <input type="password" name="password" class="form-control"
                                                id="password" required>
                                            <span class="text-danger">@error('password'){{ $message }}@enderror</span>
                                        </div>

                                        <div class="col-12">
                                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                                            <input type="Password" name="password_confirmation" class="form-control"
                                                id="password_confirmation" required>
                                            <span class="text-danger">@error('password_confirmation'){{ $message }}@enderror</span>
                                        </div>                                        

                                        <div class="col-12">
                                            <button class="btn btn-primary w-100" type="submit">Register</button>
                                        </div>
                                        <div class="col-12">
                                            <p class="small mb-0">Already have an account? 
                                                <a href="{{ route('auth.login') }}">Log in</a>
                                            </p>
                                        </div>
                                    </form>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </section>

        </div>
    </main>

</body>

</html>
