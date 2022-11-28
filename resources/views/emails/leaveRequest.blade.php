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
        <div>Hello <b>{{ $moduleFormApprover->FirstName.' '.$moduleFormApprover->LastName }},</b></div>
        <br><br>
        <div style="text-indent: 15px;">
            <a href="{{ route('leaveRequest.view', ['Id' => $leaveRequest->Id]) }}" style="color: blue;">{{ $leaveRequest->DocumentNumber }}</a> - {{ $leaveRequest->FirstName.' '.$leaveRequest->LastName }} is asking for your approval.
        </div>
        <br><br>
        <div>
            <div>Details</div>
            <div><label>Leave Type</label> {{ $leaveRequest->LeaveType }}</div>
            <div><label>Date:</label> {{ $leaveRequest->StartDate == $leaveRequest->EndDate ? date('F d, Y', strtotime($leaveRequest->StartDate)) : (date('F d', strtotime($leaveRequest->StartDate)).' - '.date('F d, Y', strtotime($leaveRequest->EndDate))) }}</div>
            <div><label>Reason:</label> {{ $leaveRequest->Reason }}</div>
        </div>
        <br><br>
        <div class="form-group">
            <a href="{{ route('external.leaveRequest.approve', ['Id' => $leaveRequest->Id]) }}" class="btn-approve">Approve</a>
            <a href="{{ route('external.leaveRequest.reject', ['Id' => $leaveRequest->Id]) }}" class="btn-reject">Reject</a>
        </div>
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