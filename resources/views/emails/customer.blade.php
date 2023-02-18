<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Opportunity</title>
    <style>
        label {
            color: rgb(85, 84, 84);
        }
    </style>
</head>
<body>

    <div>
        <div>Hi <b>{{ $user->FirstName.' '.$user->LastName }},</b></div>

        <br><br>

        @if ($path == "Capability")
            <div style="text-indent: 15px;">
                <div><?= $notification['Description'] ?></div>
            </div>

            <br><br>

            <div>
                <div>Details</div>

                @if (isset($notification['ThirdParty']) && count($notification['ThirdParty']))
                    <ul>
                        @foreach ($notification['ThirdParty'] as $dt)
                            <li>{{ $dt->Title }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        @elseif ($path == "Project Phase" || $path == "Assessment")
            <div style="text-indent: 15px;">
                Please do an assessment and indicate the manhours of the requirement for <a href="{{ $notification['Link'] }}" target="_blank">{{ $data->CustomerName }}</a>.
            </div>

            <br><br>

            <div>Thank you!</div>
        @else
        @endif

        <br><br><br>

        <div>
            <label for="">Regards,</label><br>
            <b>ePLDT - {{ env('APP_NAME') }}</b>
        </div>
        <br>
        <b style="color: darkred;"><i>NOTE: THIS IS AUTO-GENERATED. DO NOT REPLY!</i></b>
    </div>
</body>
</html>