<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>[PROD] ePLDT | Login</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="{{ asset('assets/img/epldt-suite-logo.png') }}" rel="icon">
    <link href="{{ asset('assets/img/epldt-suite-logo.png') }}" rel="epldt-suite-logo">

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
                                        <h5 class="card-title text-center pb-0 fs-4 mb-0">PROJECT EAGLE</h5>
                                        <p class="text-center small">Enter your username & password to login</p>
                                    </div>

                                    <form action=" {{ route('auth.check') }}" method="POST"
                                        class="row g-3 needs-validation">
                                        @if(Session::get('fail'))
                                            <div class="alert alert-danger">
                                                {{ Session::get('fail') }}
                                            </div>
                                        @endif

                                        @csrf
                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                        <div class="col-12">
                                            <label for="email" class="form-label">Email Address</label>
                                            <div class="input-group has-validation">
                                                <span class="input-group-text" id="inputGroupPrepend">@</span>
                                                <input type="email" name="email" class="form-control" id="email" required value="{{ old('email') }}">
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <label for="password" class="form-label">Password</label>
                                            

                                            <div class="input-group has-validation">
                                                <input type="password" name="password" class="form-control" id="password" required>
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-primary" type="button" id="btnPreviewPassword">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="remember"
                                                    value="true" id="rememberMe">
                                                <label class="form-check-label" for="rememberMe">Remember Me</label>
                                            </div>
                                        </div>
                                        <div class="col-12 mt-3">
                                            <button class="btn btn-primary w-100" type="submit"><i class="bi bi-send-fill"></i> Log In</button>
                                        </div>
                                        {{-- <div class="col-12">
                                            <p class="small mb-0">Don't have account? <a
                                                    href="{{ route('auth.register') }}">Create an account</a></p>
                                        </div> --}}
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

<script>

    $(document).ready(function() {
        $(document).on('click', '#btnPreviewPassword', function(e) {
            e.preventDefault(); 
            let isPreview = $(this).attr('preview') ?? 'true';
            if (isPreview == 'true') {
                $(this).html(`<i class="bi bi-eye-slash"></i>`);
            } else {
                $(this).html(`<i class="bi bi-eye"></i>`);
            }
            $(`[name="password"]`).attr('type', isPreview == 'true' ? 'text' : 'password');

            isPreview = isPreview == 'true' ? 'false' : 'true';
            $(this).attr('preview', isPreview);
        })
    })

</script>

</html>
