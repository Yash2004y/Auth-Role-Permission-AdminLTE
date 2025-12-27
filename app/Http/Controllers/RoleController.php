<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interfaces\RoleServiceInterface;
use App\Models\Role;
use App\Repository\PermissionRepository;
use App\Repository\RoleRepository;
use Spatie\Permission\Models\Permission;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends CustomBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private RoleRepository $roleRepository;
    private RoleServiceInterface $roleService;
    private PermissionRepository $permissionRepository;
    function __construct(RoleServiceInterface $roleService, RoleRepository $roleRepository, PermissionRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
        $this->roleRepository = $roleRepository;
        $this->roleService = $roleService;
        $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:role-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:role-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): View|JsonResponse
    {
        $permission = $this->permissionRepository->getAllPermission();

        return view('roles.index', compact('permission'));
    }
    public function list(Request $request)
    {
        $permissionIds = $request->input('permissionIds');
        $roles = $this->roleRepository->roleListQuery($permissionIds);
        return DataTables::eloquent($roles)
            ->filterColumn('created_at', function ($query, $keyword) {
                // Make formatted date searchable
                $query->whereRaw("DATE_FORMAT(roles.created_at, '%d/%m/%Y %h:%i %p') LIKE ?", ["%{$keyword}%"]);
            })
            ->addColumn('action', function ($row) {
                $editUrl = route('roles.edit', $row->id);
                $deleteUrl = route('roles.destroy', $row->id);
                $viewUrl = route('roles.show', $row->id);
                $otherData = json_encode(["_method" => "DELETE"]);
                $html = '<div class="d-flex gap-1">';

                if (auth()->user()->can('role-edit'))
                    $html .= "<a href='$editUrl' class='btn btn-sm btn-warning'><i class='fas fa-pencil-alt'></i></a>";
                if (auth()->user()->can('role-delete'))
                    $html .= "<button data-delete-url='$deleteUrl' data-id='$row->id' class='btn btn-sm btn-danger item-delete-btn' data-ajax-other-data='$otherData' data-method='POST'><i class='fas fa-trash'></i></button>";

                $html .= "<a title='$row?->description' href='$viewUrl' class='btn btn-sm btn-primary'><i class='fas fa-eye'></i></a>";

                $html .= '</div>';
                return $html;
            })->make(true);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        $permission = $this->permissionRepository->getAllPermission();
        $route = route('roles.store');
        $rolePermissions = [];
        $type = "Add";
        return view('roles.addEdit', compact('permission', 'rolePermissions', 'type', 'route'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validateData([
            'name' => 'required|unique:roles,name',
            // 'permission' => 'required',
        ], [
            "name.required" => "Please enter name",
            "name.unique" => "Role name already exists",
        ]);

        return $this->handleTransaction(function () use ($request) {

            $this->roleService->createRole($request->all());
            return sendAjaxResponse('Role created successfully', route('roles.index'));
        });
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = $this->roleRepository->getRoleById($id);

        $rolePermissions = $this->roleRepository->getRolePermissions($id);

        return view('roles.show', compact('role', 'rolePermissions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = $this->roleRepository->getRoleById($id);
        $permission = $this->permissionRepository->getAllPermission();
        $rolePermissions = $this->roleRepository->getRolePermissionIds($id);
        $rolePermissions = collect($rolePermissions)->values()->toArray();
        $type = 'Edit';
        $route = route('roles.update', $id);
        return view('roles.addEdit', compact('role', 'permission', 'rolePermissions', 'type', 'route'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validateData([
            'name' => 'required',
            // 'permission' => 'required',
        ]);

        return $this->handleTransaction(function () use ($id, $request) {
            $role = $this->roleService->updateRole($id, $request->all());
            return sendAjaxResponse('Role updated', route('roles.index'));
        });
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->handleTransaction(function () use ($id) {
            $this->roleService->deleteRole($id);
            return sendAjaxResponse('Role deleted');
        });
    }
}
