<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\admin\ProjectPhase;
use App\Models\admin\ProjectPhaseResources;
use App\Models\admin\ProjectPhaseDetails;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProjectPhaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProjectPhase::truncate();
        ProjectPhaseResources::truncate();
        ProjectPhaseDetails::truncate();

        // PROJECT PHASES
        $ppId0  = Str::uuid(); // PRE-DEPLOYMENT ACTIVITIES
        $ppId1  = Str::uuid(); // PRE-DEPLOYMENT ACTIVITIES
        $ppId2  = Str::uuid(); // PROJECT COORDINATION
        $ppId3  = config('constant.ID.PROJECT_PHASES.BUILD'); // BUILD PROJECT_PHASES
        $ppId4  = Str::uuid(); // QUALITY ASSURANCE
        $ppId5  = Str::uuid(); // TESTING
        $ppId6  = Str::uuid(); // CHANGE MANAGEMENT
        $ppId7  = Str::uuid(); // TRAINING
        $ppId8  = Str::uuid(); // DEPLOYMENT
        $ppId9  = Str::uuid(); // GO-LIVE
        $ppId10 = Str::uuid(); // POST DEPLOYMENT SUPPORT

        // DESIGNATION
        $PMDesignationId  = config('constant.ID.DESIGNATIONS.PROJECT_MANAGER');
        $GMDesignationId  = config('constant.ID.DESIGNATIONS.BA_HEAD');
        $BADesignationId  = config('constant.ID.DESIGNATIONS.BUSINESS_ANALYST');
        $TCDesignationId  = config('constant.ID.DESIGNATIONS.TECHNICAL_CONSULTANT');
        $FCDesignationId  = config('constant.ID.DESIGNATIONS.FUNCTIONAL_CONSULTANT');
        $CMDesignationId  = config('constant.ID.DESIGNATIONS.CUSTOMER_MANAGER');

        $data = [
            [
                'Id'         => $ppId0,
                'Title'      => 'Pre-Deployment Activities',
                'Percentage' => 10,
                'Required'   => 1,
                'Status'     => 1,
            ],
            [
                'Id'         => $ppId1,
                'Title'      => 'Project Coordination',
                'Percentage' => 30,
                'Required'   => 1,
                'Status'     => 1,
            ],
            [
                'Id'         => $ppId2,
                'Title'      => 'Requirement Verification',
                'Percentage' => 5,
                'Required'   => 1,
                'Status'     => 1,
            ],
            [
                'Id'         => $ppId3,
                'Title'      => 'Build',
                'Percentage' => 100,
                'Required'   => 1,
                'Status'     => 1,
            ],
            [
                'Id'         => $ppId4,
                'Title'      => 'Quality Assurance',
                'Percentage' => 15,
                'Required'   => 1,
                'Status'     => 1,
            ],
            [
                'Id'         => $ppId5,
                'Title'      => 'Testing',
                'Percentage' => 15,
                'Required'   => 1,
                'Status'     => 1,
            ],
            [
                'Id'         => $ppId6,
                'Title'      => 'Change Management',
                'Percentage' => 5,
                'Required'   => 1,
                'Status'     => 1,
            ],
            [
                'Id'         => $ppId7,
                'Title'      => 'Training',
                'Percentage' => 10,
                'Required'   => 1,
                'Status'     => 1,
            ],
            [
                'Id'         => $ppId8,
                'Title'      => 'Deployment',
                'Percentage' => 10,
                'Required'   => 1,
                'Status'     => 1,
            ],
            [
                'Id'         => $ppId9,
                'Title'      => 'Go-Live',
                'Percentage' => 10,
                'Required'   => 1,
                'Status'     => 1,
            ],
            [
                'Id'         => $ppId10,
                'Title'      => 'Post Deployment Support',
                'Percentage' => 10,
                'Required'   => 1,
                'Status'     => 1,
            ],
        ];

        $resourceData = [
            [
                'Id'             => Str::uuid(),
                'ProjectPhaseId' => $ppId0,
                'DesignationId'  => $PMDesignationId,
                'Percentage'     => 100,
            ],
            [
                'Id'             => Str::uuid(),
                'ProjectPhaseId' => $ppId1,
                'DesignationId'  => $PMDesignationId,
                'Percentage'     => 100,
            ],
            [
                'Id'             => Str::uuid(),
                'ProjectPhaseId' => $ppId2,
                'DesignationId'  => $PMDesignationId,
                'Percentage'     => 50,
            ],
            [
                'Id'             => Str::uuid(),
                'ProjectPhaseId' => $ppId2,
                'DesignationId'  => $BADesignationId,
                'Percentage'     => 50,
            ],
            [
                'Id'             => Str::uuid(),
                'ProjectPhaseId' => $ppId3,
                'DesignationId'  => $FCDesignationId,
                'Percentage'     => 50,
            ],
            [
                'Id'             => Str::uuid(),
                'ProjectPhaseId' => $ppId3,
                'DesignationId'  => $TCDesignationId,
                'Percentage'     => 50,
            ],
            [
                'Id'             => Str::uuid(),
                'ProjectPhaseId' => $ppId4,
                'DesignationId'  => $BADesignationId,
                'Percentage'     => 100,
            ],
            [
                'Id'             => Str::uuid(),
                'ProjectPhaseId' => $ppId5,
                'DesignationId'  => $BADesignationId,
                'Percentage'     => 50,
            ],
            [
                'Id'             => Str::uuid(),
                'ProjectPhaseId' => $ppId5,
                'DesignationId'  => $FCDesignationId,
                'Percentage'     => 50,
            ],
            [
                'Id'             => Str::uuid(),
                'ProjectPhaseId' => $ppId6,
                'DesignationId'  => $CMDesignationId,
                'Percentage'     => 100,
            ],
            [
                'Id'             => Str::uuid(),
                'ProjectPhaseId' => $ppId7,
                'DesignationId'  => $FCDesignationId,
                'Percentage'     => 10,
            ],
            [
                'Id'             => Str::uuid(),
                'ProjectPhaseId' => $ppId7,
                'DesignationId'  => $BADesignationId,
                'Percentage'     => 90,
            ],
            [
                'Id'             => Str::uuid(),
                'ProjectPhaseId' => $ppId8,
                'DesignationId'  => $FCDesignationId,
                'Percentage'     => 10,
            ],
            [
                'Id'             => Str::uuid(),
                'ProjectPhaseId' => $ppId8,
                'DesignationId'  => $TCDesignationId,
                'Percentage'     => 90,
            ],
            [
                'Id'             => Str::uuid(),
                'ProjectPhaseId' => $ppId9,
                'DesignationId'  => $FCDesignationId,
                'Percentage'     => 10,
            ],
            [
                'Id'             => Str::uuid(),
                'ProjectPhaseId' => $ppId9,
                'DesignationId'  => $TCDesignationId,
                'Percentage'     => 90,
            ],
            [
                'Id'             => Str::uuid(),
                'ProjectPhaseId' => $ppId10,
                'DesignationId'  => $FCDesignationId,
                'Percentage'     => 10,
            ],
            [
                'Id'             => Str::uuid(),
                'ProjectPhaseId' => $ppId10,
                'DesignationId'  => $TCDesignationId,
                'Percentage'     => 90,
            ],
        ];

        $detailsData = [
            [
                'Id'             => Str::uuid(),
                'ProjectPhaseId' => $ppId1,
                'Title'          => 'Kickoff',
                'Required'       => 1,
                'Status'         => 1,
            ],
            [
                'Id'             => Str::uuid(),
                'ProjectPhaseId' => $ppId1,
                'Title'          => 'Documentation',
                'Required'       => 1,
                'Status'         => 1,
            ],
            [
                'Id'             => Str::uuid(),
                'ProjectPhaseId' => $ppId1,
                'Title'          => 'Meetings',
                'Required'       => 1,
                'Status'         => 1,
            ],
            [
                'Id'             => Str::uuid(),
                'ProjectPhaseId' => $ppId2,
                'Title'          => 'Blueprinting',
                'Required'       => 1,
                'Status'         => 1,
            ],
            [
                'Id'             => Str::uuid(),
                'ProjectPhaseId' => $ppId2,
                'Title'          => 'Documentation',
                'Required'       => 1,
                'Status'         => 1,
            ],
            [
                'Id'             => Str::uuid(),
                'ProjectPhaseId' => $ppId2,
                'Title'          => 'Monitoring',
                'Required'       => 1,
                'Status'         => 1,
            ],
            [
                'Id'             => Str::uuid(),
                'ProjectPhaseId' => $ppId2,
                'Title'          => 'Meetings',
                'Required'       => 1,
                'Status'         => 1,
            ],
            [
                'Id'             => Str::uuid(),
                'ProjectPhaseId' => $ppId3,
                'Title'          => 'Licenses',
                'Required'       => 1,
                'Status'         => 1,
            ],
            [
                'Id'             => Str::uuid(),
                'ProjectPhaseId' => $ppId3,
                'Title'          => 'Users',
                'Required'       => 1,
                'Status'         => 1,
            ],
            [
                'Id'             => Str::uuid(),
                'ProjectPhaseId' => $ppId3,
                'Title'          => 'Permissions',
                'Required'       => 1,
                'Status'         => 1,
            ],
            [
                'Id'             => Str::uuid(),
                'ProjectPhaseId' => $ppId3,
                'Title'          => 'Org Setup',
                'Required'       => 1,
                'Status'         => 1,
            ],
            [
                'Id'             => Str::uuid(),
                'ProjectPhaseId' => $ppId3,
                'Title'          => 'Monitoring',
                'Required'       => 1,
                'Status'         => 1,
            ],
            [
                'Id'             => Str::uuid(),
                'ProjectPhaseId' => $ppId3,
                'Title'          => 'Meetings',
                'Required'       => 1,
                'Status'         => 1,
            ],
            [
                'Id'             => Str::uuid(),
                'ProjectPhaseId' => $ppId3,
                'Title'          => 'Configuration',
                'Required'       => 1,
                'Status'         => 1,
            ],
            [
                'Id'             => Str::uuid(),
                'ProjectPhaseId' => $ppId3,
                'Title'          => 'Development',
                'Required'       => 0,
                'Status'         => 1,
            ],
            [
                'Id'             => Str::uuid(),
                'ProjectPhaseId' => $ppId3,
                'Title'          => 'Integration',
                'Required'       => 0,
                'Status'         => 1,
            ],
            [
                'Id'             => Str::uuid(),
                'ProjectPhaseId' => $ppId3,
                'Title'          => 'Data Migration',
                'Required'       => 0,
                'Status'         => 1,
            ],
            [
                'Id'             => Str::uuid(),
                'ProjectPhaseId' => $ppId3,
                'Title'          => 'Analytics',
                'Required'       => 0,
                'Status'         => 1,
            ],
            [
                'Id'             => Str::uuid(),
                'ProjectPhaseId' => $ppId4,
                'Title'          => 'Internal Test',
                'Required'       => 1,
                'Status'         => 1,
            ],
            [
                'Id'             => Str::uuid(),
                'ProjectPhaseId' => $ppId4,
                'Title'          => 'Monitoring',
                'Required'       => 1,
                'Status'         => 1,
            ],
            [
                'Id'             => Str::uuid(),
                'ProjectPhaseId' => $ppId4,
                'Title'          => 'Meetings',
                'Required'       => 1,
                'Status'         => 1,
            ],
            [
                'Id'             => Str::uuid(),
                'ProjectPhaseId' => $ppId5,
                'Title'          => 'Test Scripts',
                'Required'       => 1,
                'Status'         => 1,
            ],
            [
                'Id'             => Str::uuid(),
                'ProjectPhaseId' => $ppId5,
                'Title'          => 'UAT',
                'Required'       => 1,
                'Status'         => 1,
            ],
            [
                'Id'             => Str::uuid(),
                'ProjectPhaseId' => $ppId5,
                'Title'          => 'Monitoring',
                'Required'       => 1,
                'Status'         => 1,
            ],
            [
                'Id'             => Str::uuid(),
                'ProjectPhaseId' => $ppId5,
                'Title'          => 'Meetings',
                'Required'       => 1,
                'Status'         => 1,
            ],
            [
                'Id'             => Str::uuid(),
                'ProjectPhaseId' => $ppId6,
                'Title'          => 'Communication',
                'Required'       => 1,
                'Status'         => 1,
            ],
            [
                'Id'             => Str::uuid(),
                'ProjectPhaseId' => $ppId6,
                'Title'          => 'Monitoring',
                'Required'       => 1,
                'Status'         => 1,
            ],
            [
                'Id'             => Str::uuid(),
                'ProjectPhaseId' => $ppId6,
                'Title'          => 'Meetings',
                'Required'       => 1,
                'Status'         => 1,
            ],
            [
                'Id'             => Str::uuid(),
                'ProjectPhaseId' => $ppId7,
                'Title'          => 'Admin Training',
                'Required'       => 0,
                'Status'         => 1,
            ],
            [
                'Id'             => Str::uuid(),
                'ProjectPhaseId' => $ppId7,
                'Title'          => 'Champions Training',
                'Required'       => 0,
                'Status'         => 1,
            ],
            [
                'Id'             => Str::uuid(),
                'ProjectPhaseId' => $ppId7,
                'Title'          => 'End-user Training',
                'Required'       => 0,
                'Status'         => 1,
            ],
            [
                'Id'             => Str::uuid(),
                'ProjectPhaseId' => $ppId7,
                'Title'          => 'Manuals',
                'Required'       => 1,
                'Status'         => 1,
            ],
            [
                'Id'             => Str::uuid(),
                'ProjectPhaseId' => $ppId7,
                'Title'          => 'Monitoring',
                'Required'       => 1,
                'Status'         => 1,
            ],
            [
                'Id'             => Str::uuid(),
                'ProjectPhaseId' => $ppId7,
                'Title'          => 'Meetings',
                'Required'       => 1,
                'Status'         => 1,
            ],
            [
                'Id'             => Str::uuid(),
                'ProjectPhaseId' => $ppId8,
                'Title'          => 'Deployment',
                'Required'       => 1,
                'Status'         => 1,
            ],
            [
                'Id'             => Str::uuid(),
                'ProjectPhaseId' => $ppId8,
                'Title'          => 'Smoke Test',
                'Required'       => 1,
                'Status'         => 1,
            ],
            [
                'Id'             => Str::uuid(),
                'ProjectPhaseId' => $ppId8,
                'Title'          => 'Monitoring',
                'Required'       => 1,
                'Status'         => 1,
            ],
            [
                'Id'             => Str::uuid(),
                'ProjectPhaseId' => $ppId8,
                'Title'          => 'Meetings',
                'Required'       => 1,
                'Status'         => 1,
            ],
            [
                'Id'             => Str::uuid(),
                'ProjectPhaseId' => $ppId9,
                'Title'          => 'User Access',
                'Required'       => 1,
                'Status'         => 1,
            ],
            [
                'Id'             => Str::uuid(),
                'ProjectPhaseId' => $ppId9,
                'Title'          => 'Monitoring',
                'Required'       => 1,
                'Status'         => 1,
            ],
            [
                'Id'             => Str::uuid(),
                'ProjectPhaseId' => $ppId9,
                'Title'          => 'Meetings',
                'Required'       => 1,
                'Status'         => 1,
            ],
            [
                'Id'             => Str::uuid(),
                'ProjectPhaseId' => $ppId10,
                'Title'          => 'Monitoring',
                'Required'       => 1,
                'Status'         => 1,
            ],
            [
                'Id'             => Str::uuid(),
                'ProjectPhaseId' => $ppId10,
                'Title'          => 'Meetings',
                'Required'       => 1,
                'Status'         => 1,
            ],
        ];


        ProjectPhase::insert($data);
        ProjectPhaseResources::insert($resourceData);
        ProjectPhaseDetails::insert($detailsData);

    }
}
