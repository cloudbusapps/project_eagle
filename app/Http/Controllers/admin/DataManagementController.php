<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class DataManagementController extends Controller
{
    public function index() {
        $moduleData = [];
        $modules = DB::table('modules')->where('Status', 1)->get();
        foreach ($modules as $dt) {
            $relatedTable = DB::table('module_table_related')->where('ModuleId', $dt->id)->get();
            $temp = [];
            if ($relatedTable && count($relatedTable)) {
                foreach ($relatedTable as $dt2) {
                    $temp[] = [
                        'id'        => $dt2->id,
                        'Title'     => $dt2->Title,
                        'TableName' => $dt2->TableName,
                    ];
                }
            }

            $moduleData[] = [
                'id'        => $dt->id,
                'Title'     => $dt->Title,
                'TableName' => $dt->TableName,
                'Related'   => $temp
            ];
        }

        $setupData = [
            [
                'id'        => Str::uuid(),
                'Title'     => 'Department',
                'TableName' => 'departments',
            ],
            [
                'id'        => Str::uuid(),
                'Title'     => 'Designation',
                'TableName' => 'designations',
            ],
            [
                'id'        => Str::uuid(),
                'Title'     => 'Leave Type',
                'TableName' => 'leave_types',
            ],
            [
                'id'        => Str::uuid(),
                'Title'     => 'Permission',
                'TableName' => 'permissions',
            ],
            [
                'id'        => Str::uuid(),
                'Title'     => 'Approval',
                'TableName' => 'approvals',
            ],
            [
                'id'        => Str::uuid(),
                'Title'     => 'Complexity',
                'TableName' => 'complexity',
                'Related'   => [
                    [
                        'id'        => Str::uuid(),
                        'Title'     => 'Details',
                        'TableName' => 'complexity_details'
                    ],
                ]
            ],
            [
                'id'        => Str::uuid(),
                'Title'     => 'Project Phases',
                'TableName' => 'project_phases',
                'Related'   => [
                    [
                        'id'        => Str::uuid(),
                        'Title'     => 'Details',
                        'TableName' => 'project_phases_details'
                    ],
                ]
            ],
        ];
        $moduleData = array_merge($moduleData, $setupData);

        $data = [
            'title'      => 'Data Management',
            'moduleData' => $moduleData,
        ];

        return view('admin.dataManagement.index', $data);
    }

    public function getSampleData($dataType = 'character varying') {
        switch ($dataType) {
            case 'bigint':
            case 'integer':
            case 'smallint':
                return fake()->randomDigit();
            case 'decimal':
            case 'numeric':
                return fake()->randomFloat(2);
            case 'uuid':
                return Str::uuid();
            case 'boolean':
                return fake()->boolean();
            case 'date':
                return fake()->date('Y-m-d');
            case 'year':
                return fake()->year();
            case 'timestamp':
            case 'datetime':
                return fake()->date('Y-m-d H:i:s');
            case 'json':
                return '{"key": "value"}';
            case 'text':
                return 'Sample Long text';
            case 'string':
            default:
                return 'Sample Text';
        }
    }

    public function moduleTemplate(Request $request) {
        $TableName = $request->TableName;
        $FileName  = $request->FileName;

        $columns = $data = [];
        $query = DB::select("
        SELECT 
            column_name, data_type
        FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = 'public'
            AND TABLE_NAME = '{$TableName}'");

        if ($query) {
            foreach ($query as $dt) {
                $ignoreColumns = ['CreatedById', 'UpdatedById', 'created_at', 'updated_at'];
                $columnName = $dt->column_name;
                $dataType   = $dt->data_type;
    
                if (!in_array($columnName, $ignoreColumns)) {
                    $columns[] = $columnName;
                    $data[$columnName] = $this->getSampleData($dataType);
                }
            }
    
            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$FileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            ];
    
            $callback = function() use($data, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);
                fputcsv($file, $data);
                fclose($file);
            };
    
            return response()->stream($callback, 200, $headers);
        }
        return abort(500);
    }

    public function exportModuleData(Request $request) {
        $TableName = $request->TableName;
        $FileName  = $request->FileName.'-'.date('ymdHis').'.csv';

        $columns = $data = [];
        $query = DB::select("
        SELECT 
            column_name, data_type
        FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = 'public'
            AND TABLE_NAME = '{$TableName}'");

        if ($query) {
            $tableData = DB::table($TableName)->get();

            $ignoreColumns = ['CreatedById', 'UpdatedById', 'created_at', 'updated_at'];
            foreach ($query as $dt) {
                $columnName = $dt->column_name;
                $dataType   = $dt->data_type;
    
                if (!in_array($columnName, $ignoreColumns)) {
                    $columns[] = $columnName;
                }
            }

            foreach ($tableData as $index => $dt) {
                $temp = [];
                foreach ($columns as $col) {
                    $temp["{$col}"] = $dt->{"{$col}"};
                }

                $data[] = $temp;
            }
    
            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$FileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            ];
    
            $callback = function() use($data, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);

                if (isset($data) && count($data)) {
                    foreach ($data as $dt) {
                        fputcsv($file, $dt);
                    }
                }

                fclose($file);
            };
    
            return response()->stream($callback, 200, $headers);
        }
        return abort(500);
    }

    public function importModuleData(Request $request) {
        $TableName = $request->TableName;

        return '
        <div id="invalidFeedback"></div>
        <form method="POST" action="'. route('dataManagement.importModuleData.save') .'" enctype="multipart/form-data" id="formImport">
            '. csrf_field() .'
            '. method_field('POST') .'
            <input type="hidden" name="TableName" value="'. $TableName .'">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <input type="file" class="form-control" name="File" accept=".csv" required>
                    </div>    
                </div>    
            </div>    
            <div class="modal-footer pb-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>';
    }

    public function validateModuleHeader(Request $request) {
        $TableName = $request->TableName;

        $errors = [];

        $columns = [];
        $query = DB::select("
        SELECT 
            column_name, data_type
        FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = 'public'
            AND TABLE_NAME = '{$TableName}'");

        if ($query) {
            $path = $request->file('File')->getRealPath();
            $csvData   = array_map('str_getcsv', file($path));
            $csvColumn = array_splice($csvData, 0, 1);
            $csvColumn = $csvColumn[0];

            foreach ($query as $dt) $columns[] = $dt->column_name;
    
            foreach ($csvColumn as $index => $col) {
                if (!in_array($col, $columns)) {
                    $errors[] = "Unable to find column <b>{$col}</b>";
                }
            }
        }

        return $errors;
    }

    public function importModuleDataSave(Request $request) {
        $TableName = $request->TableName;

        $errors = [];

        $columns = [];
        $query = DB::select("
        SELECT 
            column_name, data_type
        FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = 'public'
            AND TABLE_NAME = '{$TableName}'");

        if ($query) {
            $path = $request->file('File')->getRealPath();
            $csvData   = array_map('str_getcsv', file($path));
            $csvColumn = array_splice($csvData, 0, 1);
            $csvColumn = $csvColumn[0];

            foreach ($query as $dt) $columns[] = $dt->column_name;
    
            $finalData = [];
            foreach ($csvData as $key => $dt) {
                $temp = [];
                foreach ($csvColumn as $key2 => $col) {
                    if ($col == 'Id' && !$dt[$key2]) {
                        $temp[$col] = Str::uuid();
                    } else {
                        $temp[$col] = $dt[$key2];
                    }
                }
                $finalData[] = $temp;
            }

            try {
                $insert = DB::table($TableName)->upsert($finalData, 'Id');

                return redirect()
                    ->back()
                    ->with('success', 'File successfully imported');
            } catch (\Exception $e) {
                dd($e->getMessage());
                exit;

                return redirect()
                    ->back()
                    ->withErrors(["There's an error importing your file"]);
            }
        }

        return abort(500);
    }



    public function form() {
        $data = [
            'title' => "New Data Management",
            'modules' => DataManagement::where('ParentId', null)->get()
        ];
        return view('admin.modules.form', $data);
    }

    public function save(Request $request) {
        $validator = $request->validate([
            'Title'     => ['required', 'string', 'max:255'],
            'SortOrder' => ['required', 'integer'],
            'Prefix'    => ['required', 'string'],
            'Icon'      => ['mimes:png,jpeg,jpg,svg,ico', 'max:2048'],
        ]);

        $destinationPath = 'uploads/icons';
        $Icon  = $request->file('Icon');
        $Title = $request->Title;
        $WithApproval = $request->WithApproval == 'on' ? true : false;
        
        $IconStore = $request->IconStore;
        $filename = $IconStore ?? "default.png";
        if ($Icon) {
            $filenameArr = explode('.', $Icon->getClientOriginalName());
            $extension   = array_splice($filenameArr, count($filenameArr)-1, 1);
            $filename    = $Title.time().'.'.$extension[0];

            $Icon->move($destinationPath, $filename);
        }
        
        $DataManagement = new DataManagement;
        $DataManagement->ParentId  = $request->ParentId;
        $DataManagement->Title     = $request->Title;
        $DataManagement->WithApproval = $WithApproval;
        $DataManagement->SortOrder = $request->SortOrder;
        $DataManagement->Icon      = $filename;
        $DataManagement->Status    = $request->Status;
        $DataManagement->RouteName = $request->RouteName;
        $DataManagement->Prefix    = $request->Prefix;

        if ($DataManagement->save()) {
            return redirect()
                ->route('module')
                ->with('success', "<b>{$Title}</b> successfully saved!");
        } 
    }

    public function edit($id) {
        $data = [
            'title' => "Edit Data Management",
            'modules' => DataManagement::where('ParentId', null)->get(),
            'data' => DataManagement::find($id)
        ];
        return view('admin.modules.form', $data);
    }

    public function update(Request $request, $id) {
        $validator = $request->validate([
            'Title'     => ['required', 'string', 'max:255'],
            'SortOrder' => ['required', 'integer'],
            'Prefix'    => ['required', 'string'],
            'Icon'      => ['mimes:png,jpeg,jpg,svg,ico', 'max:2048'],
        ]);

        $destinationPath = 'uploads/icons';
        $Icon  = $request->file('Icon');
        $Title = $request->Title;
        $WithApproval = $request->WithApproval == 'on' ? true : false;

        $IconStore = $request->IconStore;
        $filename = $IconStore ?? "default.png";
        if ($Icon) {
            $filenameArr = explode('.', $Icon->getClientOriginalName());
            $extension   = array_splice($filenameArr, count($filenameArr)-1, 1);
            $filename    = $Title.time().'.'.$extension[0];

            $Icon->move($destinationPath, $filename);
        }
        
        $DataManagement = DataManagement::find($id);
        $DataManagement->ParentId  = $request->ParentId;
        $DataManagement->Title     = $request->Title;
        $DataManagement->WithApproval = $WithApproval;
        $DataManagement->SortOrder = $request->SortOrder;
        $DataManagement->Icon      = $filename;
        $DataManagement->Status    = $request->Status;
        $DataManagement->RouteName = $request->RouteName;
        $DataManagement->Prefix    = $request->Prefix;

        if ($DataManagement->save()) {
            return redirect()
                ->route('module')
                ->with('success', "<b>{$Title}</b> successfully updated!");
        } 
    }

    public function delete($id) {
        $DataManagement = DataManagement::find($id);
        $Title = $DataManagement->Title;

        if ($DataManagement->delete()) {
            return redirect()
                ->route('module')
                ->with('success', "<b>{$Title}</b> successfully deleted!");
        } else {
            return redirect()
                ->route('module')
                ->with('fail', "<b>{$Title}</b> failed to delete!");
        }
    }

}
