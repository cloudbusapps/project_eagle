<?php

    use Illuminate\Support\Str;
    use App\Models\admin\ModuleApproval;
    use App\Models\admin\ModuleFormApprover;

    function isFormPending($ModuleId = 0, $TableId = '') {
        $data = ModuleFormApprover::select(DB::raw('CASE WHEN SUM("Status") = 0 THEN \'0\' ELSE \'1\' END AS status'))
            ->where('ModuleId', $ModuleId)
            ->where('TableId', $TableId)
            ->limit(1)
            ->get();
        return ($data && count($data)) ? $data[0]->status == '0' : false;
    }

    function setFormApprovers($ModuleId = 0, $TableId = '') {
        $DesignationId = Auth::user()->DesignationId;
        $delete = ModuleFormApprover::where('ModuleId', $ModuleId)->where('TableId', $TableId)->delete();

        $approvers = ModuleApproval::where('ModuleId', $ModuleId)
            ->where('DesignationId', $DesignationId)
            ->get();

        $approverData = [];
        foreach ($approvers as $dt) {
            $approverData[] = [
                'Id'            => Str::uuid(),
                'ModuleId'      => $ModuleId,
                'TableId'       => $TableId,
                'Level'         => $dt['Level'],
                'ApproverId'    => $dt['ApproverId'],
                'Status'        => 0,
                'Date'          => null,
                'Remarks'       => null,
                'CreatedById' => Auth::id(),
                'UpdatedById' => Auth::id(),
            ];
        }
        if ($approverData && count($approverData)) {
            ModuleFormApprover::insert($approverData);
        }
        return true;
    }

    function getFormStatus($ModuleId = 0, $TableId = '') {
        $approvers = ModuleFormApprover::where('ModuleId', $ModuleId)
            ->where('TableId', $TableId)
            ->get(['Status']);
        $status = 0; $approvedCount = 0;
        if ($approvers && count($approvers)) {
            foreach ($approvers as $dt) {
                if ($dt['Status'] == 2) $status = 2; // REJECTED
                if ($dt['Status'] == 1) {
                    $approvedCount++;
                }
            }
            $status = count($approvers) == $approvedCount ? 1 : $status; // APPROVED
        }
        return $status;
    }