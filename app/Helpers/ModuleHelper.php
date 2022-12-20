<?php

    use App\Models\admin\Module;
    use App\Models\admin\Department;

    /**
     * It returns an array of modules that are allowed to be read by the user.
     * 
     * @param ParentId The parent id of the module.
     * 
     * @return [
     *     {
     *         "id": 1,
     *         "Title": "Dashboard",
     *         "RouteName": "dashboard",
     *         "Prefix": "dashboard"
     *     },
     *     {
     *         "id": 2,
     *         "Title": "Users",
     *         "RouteName": "users",
     *         "Prefix
     */
    function getModuleItemsData($ParentId = 0) {
        $data = [];
        $modules = Module::where('ParentId', $ParentId)
            ->where('Status', 1)
            ->orderBy('SortOrder')
            ->get();
        foreach ($modules as $module) {
            if (isReadAllowed($module['id'])) {
                $data[] = [
                    'id'        => $module['id'],
                    'Title'     => $module['Title'],
                    'RouteName' => $module['RouteName'],
                    'Prefix'    => $module['Prefix'],
                ];
            }
        }
        return $data;
    }

    /**
     * It returns a string based on the value of the parameter.
     * 
     * @param CategoryId The category ID of the module.
     * 
     * @return the string value of the category name.
     */
    function getModuleCategory($CategoryId = 0) {
        switch ($CategoryId) {
            case 1: return 'EMPLOYEE PROFILE';
            case 2: return 'UTILIZATION';
            case 3: default: return 'REPORT & DASHBOARD';
        }
    }

    /**
     * It gets all the modules from the database and then checks if the user has access to the module.
     * If the user has access to the module, it will be added to the array.
     * 
     * @return Array
     * (
     *     [0] =&gt; Array
     *         (
     *             [module] =&gt; Array
     *                 (
     *                     [id] =&gt; 1
     *                     [Title] =&gt; Master
     *                     [Prefix] =&gt; 
     *                     [RouteName] =&
     */
    function getModuleData() {
        $data = [];

        $moduleCategory = Module::select('CategoryId')
            ->groupBy('CategoryId')
            ->orderBy('CategoryId', 'ASC')
            ->get();

        foreach ($moduleCategory as $index => $category) {
            $temp = [
                'module' => getModuleCategory($category['CategoryId']),
                'index' => $index + 1,
                'items'  => []
            ];

            $modules = Module::where('Status', 1)
                ->where('ParentId', null)
                ->where('CategoryId', $category['CategoryId'])
                ->orderBy('SortOrder', 'ASC')
                ->get();

            foreach ($modules as $module) {
                $items = getModuleItemsData($module['id']);

                if (!$module['RouteName'] && count($items)) {
                    $temp['items'][] = [
                        'id'        => $module['id'],
                        'Title'     => $module['Title'],
                        'Prefix'    => $module['Prefix'],
                        'RouteName' => $module['RouteName'],
                        'Icon'      => $module['Icon'],
                        'items'     => $items,
                    ];
                } else if (isReadAllowed($module['id'])) {
                    $temp['items'][] = [
                        'id'        => $module['id'],
                        'Title'     => $module['Title'],
                        'Prefix'    => $module['Prefix'],
                        'RouteName' => $module['RouteName'],
                        'Icon'      => $module['Icon'],
                        'items'     => $items,
                    ];
                }
            }

            if ($temp['items'] && count($temp['items'])) {
                $data[] = $temp;
            }
        }
        // echo "<pre>";
        // print_r($data);
        // exit;
        return $data;
    }


    /**
     * It takes a number, and returns a string with a comma separating the thousands, and a period
     * separating the decimal places
     * 
     * @param value The value to be formatted.
     * @param prefix if true, it will add a ₱ before the amount.
     */
    function formatAmount($value = 0, $prefix = false) {
        return ($prefix ? "₱ " : "") . number_format($value, 2);
    }

    /**
     * If the difference between the current time and the time of the activity is greater than an hour,
     * return the number of hours ago. If the difference is less than an hour, return the number of
     * minutes ago. If the difference is less than a minute, return "Just now".
     * 
     * @param date The date you want to convert to a time ago.
     */
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
    
    /**
     * It returns a span element with a class of badge and a background color of either info, success,
     * danger, secondary, or warning. The text inside the span element is either "For Approval",
     * "Approved", "Rejected", "Cancelled", or "Pending".
     * 
     * @param status the status of the request
     * @param additionalText This is the text that will be displayed after the status.
     * 
     * @return a string.
     */
    function getStatusDisplay($status = 0, $additionalText = '') {
        switch ($status) {
            case 1: return "<span class='badge bg-info'>For Approval". ($additionalText ? ' - '.$additionalText : '') ."</span>";
            case 2: return "<span class='badge bg-success'>Approved". ($additionalText ? ' - '.$additionalText : '') ."</span>";
            case 3: return "<span class='badge bg-danger'>Rejected". ($additionalText ? ' - '.$additionalText : '') ."</span>";
            case 4: return "<span class='badge bg-secondary'>Cancelled". ($additionalText ? ' - '.$additionalText : '') ."</span>";
            default: return "<span class='badge bg-warning'>Pending". ($additionalText ? ' - '.$additionalText : '') ."</span>";
        }
    }

    /**
     * It takes a string like "ABC-123" and returns the number 123.
     * 
     * @param code The document code, e.g. "DOC-1"
     * 
     * @return The last document number.
     */
    function getLastDocumentNumber($code = null) {
        if ($code) {
            $array = explode('-', $code);
            $number = (int) ($array[1]);
            return $number + 1;
        }
        return 1;
    }

    /**
     * It takes a number and returns a string with a prefix and a number with leading zeros.
     * 
     * @param prefix The prefix of the document number.
     * @param number The number to be formatted.
     * 
     * @return  = 'ABC'
     *      = 1
     *      = '1'
     *      = 1
     *      = 6
     *      = '000001'
     *     return 'ABC-000001'
     */
    function generateDocumentNumber($prefix = '', $number) {
        $strNumber  = (string) $number;
        $strLength  = strlen($strNumber);
        $baseNumber = 6;

        if ($strLength < $baseNumber) {
            $strNumber = str_repeat('0', $baseNumber - $strLength).$strNumber;
        }
        return "{$prefix}-{$strNumber}";
    }


    /**
     * It returns the UserId of the DepartmentHead of the Department with the given DepartmentId.
     * 
     * @param DepartmentId The id of the department you want to get the head of.
     * 
     * @return The return value is the UserId of the Department.
     */
    function getDepartmentHeadId($DepartmentId = '') {
        $data = Department::find($DepartmentId);
        return $data ? $data->UserId : null;
    }