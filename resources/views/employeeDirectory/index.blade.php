@extends('layouts.app')

@section('content')

<main id="main" class="main">

    <div class="page-toolbar px-xl-4 px-sm-2 px-0 py-3">
        <div class="container-fluid">
            <div class="row g-3 align-items-center">
                <div class="col">
                    <h4 class="mb-0">{{ $title }}</h4>
                    <ol class="breadcrumb bg-transparent mb-0">
                        <li class="breadcrumb-item"><a class="text-secondary" href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>  

    <div class="page-body px-xl-4 px-sm-2 px-0 py-lg-2 py-1 mt-0">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 pb-3">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('employeeDirectory') }}" method="GET">
                                <div class="row">
                                    <div class="col-md-4 col-sm-12">
                                        <div class="form-group">
                                            <label for="filterBy">Filter By</label>
                                            <select class="form-control" name="filterBy" id="filterBy" data="{{ $filterBy }}" select2>
                                                <option value="All" {{ $filterBy == 'All' ? 'selected' : '' }}>All</option>
            
                                                @foreach ($filterData as $key => $dt)
                                                    <option value="{{ $key }}" data="{{ $dt }}" {{ $filterBy == $key ? 'selected' : '' }}>{{ $key }}</option>
                                                @endforeach
            
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <div class="form-group">
                                            <label for="search">Search</label>
                                            <input type="text" name="search" id="search" list="searchData" class="form-control" value="{{ $search }}" placeholder="Search for..." autocomplete="off">
            
                                            <datalist id="searchData">
                                            @foreach ($searchData as $key => $dt)
                                                <option value="{{ $dt->optval }}">
                                            @endforeach
                                            </datalist>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <button class="btn btn-success w-100 h-100 btnSubmit" type="submit">
                                            <i class="bi bi-search"></i> Submit
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
    
                @if (count($users))
                @foreach ($users as $user)
    
                <div class="col-md-4 col-sm-12">
                    <div class="card" style="height: 95%;">
                        <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                            <img src="{{ asset('uploads/profile/' . $user['Profile'] ?? 'default.png') }}" alt="Profile" class="rounded" width="150" height="150">
                            <div class="my-3 d-flex flex-column justify-content-center text-center">
                                <a href="{{ route('user.viewProfile', ['Id' => $user['Id']]) }}" class="btnViewProfile">
                                    <h5>{{ $user['full_name'] }}</h5>
                                </a>
                                <small class="mb-0 fw-bold">{{ ($user['EmployeeNumber'] ?? '-') . ' | ' . ($user['designation'] ?? '-') }}</small>
                                <small>{{ $user['email'] }}</small>
                            </div>
                        </div>
                    </div>
                </div>
    
                @endforeach
    
                <div class="d-flex justify-content-center">
                    {{-- {!! $users->links() !!} --}}
                </div>
    
                @endif
    
            </div>
        </div>
    </div>

</main>

<script>

    $(document).ready(function() {

        // ----- CHANGE FILTER BY -----
        $(document).on('change', `[name="filterBy"]`, function() {
            let filterBy = $(this).val();

            let data = $('option:selected', this).attr('data');
                data = data ? JSON.parse(data) : null;
            
            let optionHTML = ``;
            if (data && data.length) {
                data.forEach(dt => {
                    let { optval } = dt;
                    optionHTML += `<option value="${optval}">`;
                })
            }
            $(`datalist[id="searchData"]`).html(optionHTML);
        })
        // ----- END CHANGE FILTER BY -----


        // ----- BUTTON SUBMIT -----
        $(document).on('click', '.btnSubmit', function() {
            let filterBy = $(`[name="filterBy"]`).val();
            let search   = $(`[name="search"]`).val();
        })
        // ----- END BUTTON SUBMIT -----

    })

</script>

@endsection