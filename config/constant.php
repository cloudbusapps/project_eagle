<?php

    use Illuminate\Support\Str;

    return [
        'ID' => [
            'DEPARTMENTS' => [
                'CLOUD_BUSINESS_APPLICATION' => Str::uuid(),
                'TECHNOLOGY_CONSULTING'      => Str::uuid(),
            ],
            'LEAVE_TYPES' => [
                'SICK_LEAVE'     => Str::uuid(),
                'VACATION_LEAVE' => Str::uuid(),
            ],
        ],
    ];