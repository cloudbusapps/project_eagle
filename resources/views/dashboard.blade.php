@extends('layouts.app')

@section('content')

<main id="main" class="main">
  <div class="page-toolbar px-xl-4 px-sm-2 px-0 py-3">
    <div class="container-fluid">
      <div class="row g-3 align-items-center">
        <div class="col">
          <h4 class="mb-0">Dashboard</h4>
          <ol class="breadcrumb bg-transparent mb-0">
            <li class="breadcrumb-item"><a class="text-secondary" href="#">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
          </ol>
        </div>
        <div class="col text-end">
          <h6 class="mb-0 fw-bold" id="realTime">{{ date('h:i:s A') }}</h6>
          <small>{{ date('F d, Y') }}</small>
        </div>
      </div>
    </div>
  </div>  

  <div class="page-body px-xl-4 px-sm-2 px-0 py-lg-2 py-1 mt-0">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-8">
            {{-- <div class="col">
              <div class="card">
                <div class="card-body">
                  <div class="text-muted text-uppercase small">Total Users</div>
                  <div class="mt-1">
                    <a href="{{ route('employeeDirectory') }}" class="fw-bold h4 mb-0">{{ $total['users'] ?? 0 }}</a>
                  </div>
                </div>
              </div>
            </div>
            <div class="col">
              <div class="card">
                <div class="card-body">
                  <div class="text-muted text-uppercase small">Total Projects</div>
                  <div class="mt-1">
                    <a href="{{ route('projects') }}" class="fw-bold h4 mb-0">{{ $total['projects'] ?? 0 }}</a>
                  </div>
                </div>
              </div>
            </div>
            <div class="col">
              <div class="card">
                <div class="card-body">
                  <div class="text-muted text-uppercase small">Stock In</div>
                  <div class="mt-1">
                    <span class="fw-bold h4 mb-0">55</span>
                    <span class="text-success ms-1">Unit</span>
                  </div>
                </div>
              </div>
            </div> --}}
            {{-- {{ json_encode($leavesData)}} --}}
          @if (isAdminOrHead())
          <div class="card">
            <div class="card-body">
              <div class="fw-bold text-uppercase text-center">Summary of Leaves</div>
              <div class="row mb-1">
                @foreach ($leaveTypes as $leaveType)
                  <div class="col-sm mt-1 text-center">
                    <div class="text-truncate">{{ $leaveType['Name'] }}</div>
                    <a class="fw-bold h4 mb-0">{{ $leaveType['totalLeave'] ?? 0 }}</a>
                  </div>
                @endforeach
              </div>
              <table id="leaveSummary" class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Employee Name</th>
                        <th>Leave Type</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                  @foreach ($leavesData as $index=>$leaveData)
                    <tr>
                      <td>{{ $index+1 }}</td>
                      <td>
                        <a href="{{ route('leaveRequest.view', ['Id' => $leaveData['Id']]) }}" >{{ $leaveData['FirstName'].' '.$leaveData['LastName'] }}</a>
                      </td>
                      <td>{{ $leaveData['LeaveType'] }}</td>
                      <td>
                        {{ $leaveData->StartDate == $leaveData->EndDate ? 
                          (date('F d, Y', strtotime($leaveData->StartDate))) :
                          (date('M d', strtotime($leaveData->StartDate)).' - '.date('M d, Y', strtotime($leaveData->EndDate)))
                          }}
                      </td>
                    </tr>
                  @endforeach
                </tbody>
            </table>
            </div>
          </div>
          @endif
             
        </div>
        <div class="col-md-4">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Recent Activity</h5>
              <div class="activity">

                  @if (count($activities))
                  <ul class="timeline">
                  @foreach ($activities as $act)
                  <li class="timeline-item">
                      <small class="activite-label w-25">{{ activityTime($act['created_at']) }}</small>
                      <small class="w-75">
                          <?= $act['description'] ?>
                      </small>
                    </li>
                  @endforeach
                  </ul>
                  @else
                  <div class="text-center text-muted"><h6>No activities yet</h6></div>
                  @endif

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
<script>
   $(document).ready(function() {

    const leaveSummaryTable = $('#leaveSummary').DataTable();
    let column_index = 0;
    function filterLeave(leaveType){
      leaveSummaryTable.columns(column_index).search(leaveType, true, false).draw();
    }
   })
</script>
    
@endsection

