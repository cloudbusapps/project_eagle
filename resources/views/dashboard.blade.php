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
        <div class="col-12">
          <div class="row">
            <div style="max-height: 600px;" class="col mb-3">
              <div class="row">
                <div class="col">
                  <div class="card">
                    <div class="card-body">
                      <div class="text-muted text-uppercase small">For Approval Leave</div>
                      <div class="mt-1">
                        <a href="{{ route('leaveRequest') }}" class="fw-bold h4 mb-0">{{ $total['pendingLeave'] ?? 0 }}</a>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col">
                  <div class="card">
                    <div class="card-body">
                      <div class="text-muted text-uppercase small">Approved Leave</div>
                      <div class="mt-1">
                        <a href="{{ route('employeeDirectory') }}" class="fw-bold h4 mb-0">{{ $total['approvedLeave'] ?? 0 }}</a>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col">
                  <div class="card">
                    <div class="card-body">
                      <div class="text-muted text-uppercase small">Rejected Leave</div>
                      <div class="mt-1">
                        <a href="{{ route('employeeDirectory') }}" class="fw-bold h4 mb-0">{{ $total['rejectedLeave'] ?? 0 }}</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col mt-3">
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title pb-3">Upcoming Leave</h5>
                    <table id="tableUpcomingLeave" style="width:100%;" class="table table-striped table-hover">
                      <thead>
                          <tr>
                              <th>#</th>
                              <th>Document Number</th>
                              <th>Employee Name</th>
                              <th>Leave Type</th>
                              <th>Date</th>
                          </tr>
                      </thead>
                      <tbody>
                        @foreach ($upcomingLeaves as $index=>$upcomingLeave)
                          <tr>
                            <td>{{ $index+1 }}</td>
                            <td>
                              <a href="{{ route('leaveRequest.view', ['Id' => $upcomingLeave['Id']]) }}" >{{ $upcomingLeave['DocumentNumber']}}</a>
                            </td>
                            <td>{{ $upcomingLeave['FirstName'].' '.$upcomingLeave['LastName'] }}</td>
                            <td>{{ $upcomingLeave['LeaveType'] }}</td>
                            <td>
                              {{ $upcomingLeave->StartDate == $upcomingLeave->EndDate ? 
                                (date('F d, Y', strtotime($upcomingLeave->StartDate))) :
                                (date('M d', strtotime($upcomingLeave->StartDate)).' - '.date('M d, Y', strtotime($upcomingLeave->EndDate)))
                                }}
                            </td>
                          </tr>
                        @endforeach
                      </tbody>
                  </table>
                  </div>
                </div>
              </div>
            </div>
            <div class="col" style="max-height: 600px">
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

        @if (isAdminOrHead())
            <div class="col mt-3">
              <div class="row">
                @foreach ($leaveTypes as $leaveType)
                  <div class="col mb-3">
                    <div class="card">
                      <div class="card-body">
                        <span class=" text-truncate text-muted text-uppercase small">{{ $leaveType['Name'] }}</span>
                        <div class="mt-1">
                          <a href="javascript:void(0);" class="fw-bold h4 mb-0 filterType pe-auto">{{ $leaveType['totalLeave'] ?? 0 }}</a>
                        </div>
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title pb-3">Summary of Leave</h5>
                  <table id="leaveSummary" style="width:100%;" class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Document Number</th>
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
                            <a href="{{ route('leaveRequest.view', ['Id' => $leaveData['Id']]) }}" >{{ $leaveData['DocumentNumber']}}</a>
                          </td>
                          <td>{{ $leaveData['FirstName'].' '.$leaveData['LastName'] }}</td>
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
            </div>
            @endif
      </div>
    </div>
  </div>
</main>
<script>
   $(document).ready(function() {
    
    let tempLeaveValue = '';

    $(document).on('click','.filterType',function(e){
      let leaveType = $(this).closest('div').prev().text();
      let textColor = $(this);
      if(tempLeaveValue==leaveType){
        filterLeave('');
        tempLeaveValue = '';
        textColor.removeClass('text-success')
        
      } else{
        filterLeave(leaveType);
        tempLeaveValue = leaveType;
        $('.filterType').removeClass('text-success')
        textColor.addClass('text-success')
      }

      
    })

    const leaveSummaryTable = $('#leaveSummary')
    .DataTable({
      scrollX: true,
      scrollY: '300px',
      sorting: [],
      scrollCollapse: true,
    });

    const tableUpcomingLeave = $('#tableUpcomingLeave')
    .DataTable({
      scrollX: true,
      scrollY: '300px',
      sorting: [],
      scrollCollapse: true,
      columnDefs: [
                    { targets: 0,  width: 10  },
                    { targets: 1,  width: 80 },
                    { targets: 2,  width: 90 },
                    { targets: 3,  width: 80 },
                    { targets: 4,  width: 70 },
                ],
    });
    
    let column_index = 0;
    function filterLeave(leaveType){
      leaveSummaryTable.columns(3).search(leaveType, true, false).draw();
    }
   })
</script>
    
@endsection

