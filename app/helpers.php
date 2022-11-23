<?php

    use App\Models\Module;

    function formatAmount($value = 0, $prefix = false) {
        return ($prefix ? "₱ " : "") . number_format($value, 2);
    }

    function getModuleItemsData($ParentId = 0) {
        $data = [];
        $modules = Module::where('ParentId', $ParentId)
            ->where('Status', 'Active')
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

    function getModuleData() {
        $data = [];

        $modules = Module::where('Status', 'Active')
            ->where('ParentId', null)
            ->orderBy('SortOrder', 'ASC')
            ->get();

        foreach ($modules as $module) {
            $data[] = [
                'id'        => $module['id'],
                'Title'     => $module['Title'],
                'Prefix'    => $module['Prefix'],
                'RouteName' => $module['RouteName'],
                'Icon'      => $module['Icon'],
                'items'     => getModuleItemsData($module['id'])
            ];
        }

        // echo "<pre>";
        // print_r($data);
        // exit;

        return $data;
    }