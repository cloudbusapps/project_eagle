<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=Edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="icon" href="{{ asset('assets/img/epldt-suite-logo.png') }}" type="image/x-icon">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="base-url" content="{{ URL::to('/') }}">
  <title>ePLDT - {{ $title ?? '' }}</title>

  <!-- Application vendor css url -->
  <link rel="stylesheet" href="{{ asset('assets/cssbundle/daterangepicker.min.css') }}">
  <!-- project css file  -->
  <link rel="stylesheet" href="{{ asset('assets/css/luno-style.css') }}">
  <!-- Jquery Core Js -->
  <script src="{{ asset('assets/js/plugins.js') }}"></script>

  {{-- Font awesome --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  
  <link rel="stylesheet" href="{{ asset('assets/css/jquery-confirm.min.css') }}">

  <script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>

</head>

<body class="layout-1 font-raleway radius-0" data-luno="theme-green">
  