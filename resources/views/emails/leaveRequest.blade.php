<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Leave Request</title>
    <style>
        label {
            color: rgb(85, 84, 84);
        }
        .btn-approve {
            background: green;
            border: 1px solid green;
            padding: 2px 10px;
            border-radius: 5px;
            color: white;
            text-decoration: none;
        }
        .btn-reject {
            background: red;
            border: 1px solid red;
            padding: 2px 10px;
            border-radius: 5px;
            color: white;
            text-decoration: none;
        }
        .form-group {
            display: flex;
            gap: 10px;
        }
    </style>
</head>
<body>
    <div>
        <div>Hello <b>{{ $approver->FirstName.' '.$approver->LastName }},</b></div>
        <br><br>
        @if ($data->Status == 2) <!-- APPROVED -->
            <div style="text-indent: 15px;">
                <a href="{{ route('leaveRequest.view', ['Id' => $data->Id]) }}" style="color: blue;">{{ $data->DocumentNumber }}</a> - Your request has been approved.
            </div>
        @elseif ($data->Status == 3) <!-- REJECTED -->
            <div style="text-indent: 15px;">
                <a href="{{ route('leaveRequest.view', ['Id' => $data->Id]) }}" style="color: blue;">{{ $data->DocumentNumber }}</a> - Your request has been rejected.
            </div>
        @else
            <div style="text-indent: 15px;">
                <a href="{{ route('leaveRequest.view', ['Id' => $data->Id]) }}" style="color: blue;">{{ $data->DocumentNumber }}</a> - {{ $data->FirstName.' '.$data->LastName }} is asking for your approval.
            </div>
            <br><br>
            <div>
                <div>Details</div>
                <div><label>Leave Type</label> {{ $data->LeaveType }}</div>
                <div><label>Date:</label> {{ $data->StartDate == $data->EndDate ? date('F d, Y', strtotime($data->StartDate)) : (date('F d', strtotime($data->StartDate)).' - '.date('F d, Y', strtotime($data->EndDate))) }}</div>
                <div><label>Reason:</label> {{ $data->Reason }}</div>
            </div>
            <br><br>
            <div class="form-group">
                <a href="{{ route('external.leaveRequest.approve', ['Id' => $data->Id]) }}" class="btn-approve">Approve</a>
                <a href="{{ route('external.leaveRequest.reject', ['Id' => $data->Id]) }}" class="btn-reject">Reject</a>
            </div>
        @endif

        <br><br><br>
        <div>
            <label for="">Regards,</label><br>
            <b>ePLDT - Project Eagle</b>
        </div>
        <br>
        <b style="color: darkred;"><i>NOTE: THIS IS AUTO-GENERATED. DO NOT REPLY!</i></b>
    </div>
</body>
</html>