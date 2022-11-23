@extends('layouts.app')

@section('content')

<main id="main" class="main">

    <div class="pagetitle">
        <h1>{{ $title }}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">User</a></li>
                <li class="breadcrumb-item"><a href="{{ route('user.viewProfile') }}">Profile</a></li>
                <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
        </nav>
    </div>

    <section class="section dashboard">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert"> 
                            @foreach ($errors->all() as $error)
                                <div>
                                    <i class="bi bi-exclamation-octagon me-1"></i>
                                    {{ $error }}
                                </div>
                            @endforeach
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif
        
                        <form method="POST" action="{{ route('user.updateProfileImage', ['Id' => $data['Id']]) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="d-flex flex-column align-items-center">
                                <div id="my_camera"></div>
                                <div class="display-image">
                                    <img src="{{ asset('uploads/profile/' . ($data['Image'] ?? 'default.png')) }}" width="200" height="200" alt="Profile"
                                        class="rounded-circle mb-2 preview-image">
                                </div>
                                <input type="file" name="Profile" id="Profile" style="display: none;">
                                <div>
                                    <button type="button" class="btn btn-outline-primary" onclick="$(`#Profile`).trigger(`click`)">Browse</button>
                                    <button type="button" class="btn btn-outline-info btnCamera">Camera</button>
                                </div>
                            </div>
                            <div class="modal-footer mt-3 pb-0">
                                <a href="{{ route('user.viewProfile') }}" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-warning">Update</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>

        </div>
    </section>

</main>

<script src="{{ asset('assets/vendor/webcamjs/webcam.min.js') }}"></script>
<script>

    $(document).ready(function() {

        // ----- SETUP CAMERA -----
        Webcam.set({
            // live preview size
            width: 320,
            height: 240,
            
            // device capture size
            dest_width: 640,
            dest_height: 480,
            
            // final cropped size
            crop_width: 480,
            crop_height: 480,
            
            // format and quality
            image_format: 'jpeg',
            jpeg_quality: 90,
            
            // flip horizontal (mirror mode)
            flip_horiz: true
        });
        
        let shutter = new Audio();
		shutter.autoplay = false;
		shutter.src = '/assets/audio/' + (navigator.userAgent.match(/Firefox/) ? 'shutter.ogg' : 'shutter.mp3');
        // shutter.play();
        // ----- END SETUP CAMERA -----


        // ----- SELECT PROFILE -----
        $(document).on('change', `[name="Profile"]`, function() {
            let [file] = this.files;
            if (file) {
                $(`img.preview-image`).attr('src', URL.createObjectURL(file));
            }
        })
        // ----- END SELECT PROFILE -----

        
        // ----- BUTTON CAMERA -----
        $(document).on('click', '.btnCamera', function() {
            // Webcam.reset();
            Webcam.on('error', function() {
                alert('Please give permission to access your webcam');
            });
            // Webcam.attach('#display-image');
            Webcam.attach('#my_camera');
        })
        // Webcam.on('error', function() {
        //     alert('Please give permission to access your webcam');
        // });
        // Webcam.attach('#my-camera');

    })


</script>

@endsection