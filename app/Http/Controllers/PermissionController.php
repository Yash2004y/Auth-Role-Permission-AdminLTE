<?php

namespace App\Http\Controllers;

use App\Interfaces\PermissionServiceInterface;
use App\Models\Permission;
use App\Repository\PermissionRepository;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends CustomBaseController
{
    private PermissionRepository $permissionRepository;
    private PermissionServiceInterface $permissionService;
        function __construct(PermissionRepository $permissionRepository,PermissionServiceInterface $permissionService)
    {
        $this->permissionRepository = $permissionRepository;
        $this->permissionService = $permissionService;

        $this->middleware('permission:permission-list|permission-create|permission-edit|permission-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:permission-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:permission-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:permission-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('permissions.index');
    }

    public function list(Request $request)
    {
        $permissions = $this->permissionRepository->permissionListQuery();
        return DataTables::eloquent($permissions)
            ->filterColumn('created_at', function ($query, $keyword) {
                // Make formatted date searchable
                $query->whereRaw("DATE_FORMAT(permissions.created_at, '%d/%m/%Y %h:%i %p') LIKE ?", ["%{$keyword}%"]);
            })
            ->addColumn('action', function ($row) {
                $editUrl = route('permissions.edit', $row->id);
                $deleteUrl = route('permissions.destroy', $row->id);
                $otherData = json_encode(["_method" => "DELETE"]);
                $html = "<div class='d-flex gap-1'>";
                if(auth()->user()->can('permission-edit')){
                    $html .= "<button data-modal-url='$editUrl' class='btn btn-sm btn-warning modalOpen'><i class='fas fa-pencil-alt'></i></button>";
                }
                if(auth()->user()->can('permission-delete'))
                {
                    $html .= "<button data-delete-url='$deleteUrl' data-id='$row->id' class='btn btn-sm btn-danger item-delete-btn' data-ajax-other-data='$otherData' data-method='POST'><i class='fas fa-trash'></i></button>";
                }

                $html .= "</div>";
                return $html;
            })
            ->make(true);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $id = $request->input('id');
        return $this->handleException(function()use($request){

            $modal = $this->modal();
            return sendAjaxModalResponse("Permission modal",$modal);
        });

    }

    public function modal($permissionId=null){
        $permission = null;
        $type = "Add";
        if(!empty($permissionId))
        {
            $type = "Edit";
            $permission = $this->permissionRepository->getById($permissionId);
        }
        return view('permissions.addEditModal',compact('permission','type'))->render();
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $id = $request->input('id',0);
        $this->validateData([
            'permission'=>'required|unique:permissions,name,'.$id,
        ]);
        return $this->handleTransaction(function()use($request,$id){
            $permission = $this->permissionService->updateOrCreatePermission(array_merge($request->all(),['id'=>$id]));
            return sendAjaxResponse($id != 0 ? 'Permission updated' : 'Permission added');
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return $this->handleException(function()use($id){

            $modal = $this->modal($id);
            return sendAjaxModalResponse("Permission modal",$modal);
        });
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->handleTransaction(function()use($id){
            $this->permissionService->deletePermission($id);
            return sendAjaxResponse('Permission deleted successfully.');
        });
    }
}
