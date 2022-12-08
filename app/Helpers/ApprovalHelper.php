<?php

    use Illuminate\Support\Str;
    use App\Models\admin\ModuleApproval;
    use App\Models\admin\ModuleFormApprover;

    function setFormApprovers($ModuleId = 0, $TableId = '') {
        $DesignationId = Auth::user()->DesignationId;
        $delete = ModuleFormApprover::where('ModuleId', $ModuleId)->where('TableId', $TableId)->delete();

        $approvers = ModuleApproval::where('ModuleId', $ModuleId)
            ->where('DesignationId', $DesignationId)
            ->get();

        $approverData = [];
        foreach ($approvers as $index => $dt) {
            $approverData[] = [
                'Id'            => Str::uuid(),
                'ModuleId'      => $ModuleId,
                'TableId'       => $TableId,
                'Level'         => $dt['Level'],
                'ApproverId'    => $dt['ApproverId'],
                'Status'        => $index == 0 ? 1 : 0,
                'Date'          => null,
                'Remarks'       => null,
                'CreatedById'   => Auth::id(),
                'UpdatedById'   => Auth::id(),
            ];
        }
        if ($approverData && count($approverData)) {
            ModuleFormApprover::insert($approverData);
        }
        return true;
    }


    function isFormPending($ModuleId = 0, $TableId = '') {
        $data = ModuleFormApprover::select(DB::raw('CASE WHEN SUM("Status") = 1 THEN \'true\' ELSE \'false\' END AS status'))
            ->where('ModuleId', $ModuleId)
            ->where('TableId', $TableId)
            ->first();
        return $data ? $data->status == 'true' : false;
    }

    function getFormStatus($ModuleId = 0, $TableId = '') {
        $approvers = ModuleFormApprover::where('ModuleId', $ModuleId)
            ->where('TableId', $TableId)
            ->get(['Status']);

        $status = 1; 
        $approvedCount = 0;
        
        if ($approvers && count($approvers)) {
            foreach ($approvers as $dt) {
                if ($dt['Status'] == 3) $status = 3; // REJECTED
                if ($dt['Status'] == 2) {
                    $approvedCount++;
                }
            }
            $status = count($approvers) == $approvedCount ? 2 : $status; // APPROVED
        }
        return $status;
    }

    function getCurrentApprover($ModuleId = 0, $TableId = '') {
        $approver = ModuleFormApprover::select('module_form_approvers.ApproverId', 'u.FirstName', 'u.LastName')
            ->leftJoin('users AS u', 'u.Id', 'module_form_approvers.ApproverId')
            ->where('ModuleId', $ModuleId)
            ->where('TableId', $TableId)
            ->where('module_form_approvers.Status', 1)
            ->orderBy('Level', 'ASC')
            ->first();
        return $approver ?? [];
    }