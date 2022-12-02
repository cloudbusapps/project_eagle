<?php

    use App\Models\admin\Module;

    function formatAmount($value = 0, $prefix = false) {
        return ($prefix ? "₱ " : "") . number_format($value, 2);
    }

    function getModuleItemsData($ParentId = 0) {
        $data = [];
        $modules = Module::where('ParentId', $ParentId)
            ->where('Status', 1)
            ->orderBy('SortOrder')
            ->get();
        foreach ($modules as $module) {
            $data[] = [
                'id'        => $module['id'],
                'Title'     => $module['Title'],
                'RouteName' => $module['RouteName'],
                'Prefix'    => $module['Prefix'],
            ];
        }
        return $data;
    }

    function getModuleCategory($CategoryId = 0) {
        switch ($CategoryId) {
            case 1: return 'EMPLOYEE PROFILE';
            case 2: return 'UTILIZATION';
            case 3: default: return 'REPORT & DASHBOARD';
        }
    }

    function getModuleData() {
        $data = [];

        $moduleCategory = Module::select('CategoryId')
            ->groupBy('CategoryId')
            ->orderBy('CategoryId', 'ASC')
            ->get();

        foreach ($moduleCategory as $index => $category) {
            $temp = [
                'module' => getModuleCategory($category['CategoryId']),
                'items'  => []
            ];

            $modules = Module::where('Status', 1)
                ->where('ParentId', null)
                ->where('CategoryId', $category['CategoryId'])
                ->orderBy('SortOrder', 'ASC')
                ->get();

            foreach ($modules as $module) {
                $temp['items'][] = [
                    'id'        => $module['id'],
                    'Title'     => $module['Title'],
                    'Prefix'    => $module['Prefix'],
                    'RouteName' => $module['RouteName'],
                    'Icon'      => $module['Icon'],
                    'items'     => getModuleItemsData($module['id'])
                ];
            }

            $data[] = $temp;
        }
        // echo "<pre>";
        // print_r($data);
        // exit;
        return $data;
    }


    function activityTime($date) {
        $today = now();
        $date1 = new DateTime($date);
        $date2 = new DateTime(now());
        $interval = $date1->diff($date2);
        $hours = (float) $interval->format('%H');
        $minutes = (float) $interval->format('%I');
        if ($hours > 0) {
            return "{$hours} hr ago";
        } else if ($minutes > 0) {
            return "{$minutes} min ago";
        } else {
            return "Just now";
        }
    }

    function getStatusDisplay($status = 0, $additionalText = '') {
        switch ($status) {
            case 1: return "<span class='badge bg-success'>Approved". ($additionalText ? ' - '.$additionalText : '') ."</span>";
            case 2: return "<span class='badge bg-danger'>Rejected". ($additionalText ? ' - '.$additionalText : '') ."</span>";
            case 3: return "<span class='badge bg-secondary'>Cancelled". ($additionalText ? ' - '.$additionalText : '') ."</span>";
            default: return "<span class='badge bg-info'>For Approval". ($additionalText ? ' - '.$additionalText : '') ."</span>";
        }
    }

    function getLastDocumentNumber($code = null) {
        if ($code) {
            $array = explode('-', $code);
            $number = (int) ($array[1]);
            return $number + 1;
        }
        return 1;
    }

    function generateDocumentNumber($prefix = '', $number) {
        $strNumber  = (string) $number;
        $strLength  = strlen($strNumber);
        $baseNumber = 6;

        if ($strLength < $baseNumber) {
            $strNumber = str_repeat('0', $baseNumber - $strLength).$strNumber;
        }
        return "{$prefix}-{$strNumber}";
    }