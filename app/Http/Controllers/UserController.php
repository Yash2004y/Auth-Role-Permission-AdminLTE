<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Role as ModelsRole;
use App\Models\User;
use Spatie\Permission\Models\Role;
use DB;
use Exception;
use Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class UserController extends CustomBaseController
{
      function __construct()
    {
        $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:user-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:user-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $roles = ModelsRole::all();
        return view('users.index',compact('roles'));
    }
    public function list(Request $request)
    {
        $data = User::with('roles')->leftJoin('users as addBy', 'users.add_by', 'addBy.id')
        ->select(['users.id', 'users.name', 'users.email', 'users.created_at', 'addBy.name as addByName','users.image']);
        $role_id = $request->input('role_id');
        $data->when($role_id,function($q)use($role_id){
            $q->whereHas('roles',function($q)use($role_id){
                    $q->where('id',$role_id);
                });
        });
        return DataTables::eloquent($data)
            ->filterColumn('created_at', function ($query, $keyword) {
                // Make formatted date searchable
                $query->whereRaw("DATE_FORMAT(users.created_at, '%d/%m/%Y %h:%i %p') LIKE ?", ["%{$keyword}%"]);
            })
            ->filterColumn('roles', function ($query, $keyword) {
                // Make formatted date searchable
                $query->whereHas('roles',function($q)use($keyword){
                    $q->where('name','like',"%{$keyword}%");
                });
            })
            ->addColumn('image',function($row){
                if(!empty($row->image)){
                    return "<img src='$row->image' style='width:50px;height:50px;' class='img img-thumbnail' />";
                }
                return "<img src='".asset('ProjectImages/placeholder.png')."' style='width:50px;height:50px;' class='img img-thumbnail' />";
            })
            ->addColumn('action', function ($row) {
                $editUrl = route('users.edit', $row->id);
                $deleteUrl = route('users.destroy', $row->id);
                $otherData = json_encode(["_method" => "DELETE"]);
                $html = '<div class="d-flex gap-1">';

                if(auth()->user()->can('user-edit'))
                    $html .= "<a href='$editUrl' class='btn btn-sm btn-warning'><i class='fas fa-pencil-alt'></i></a>";
                if(auth()->user()->can('user-delete'))
                    $html .= "<button data-delete-url='$deleteUrl' data-id='$row->id' class='btn btn-sm btn-danger item-delete-btn' data-ajax-other-data='$otherData' data-method='POST'><i class='fas fa-trash'></i></button>";
                $html .= '</div>';
                    return $html;
            })
             ->rawColumns(['image','action'])
            // ->addIndexColumn()
            ->make(true);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        $roles = Role::pluck('name', 'name')->all();
        $type = "Add";
        $route = route('users.store');

        return view('users.addEdit', compact('roles', 'type', 'route'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request->all();
        $error = $this->validateData([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required'
        ]);
        // if(!empty($error)){
        //     return $error;
        // }

        return $this->handleTransaction(function () use ($request) {
            $input = $request->all();
            $input['password'] = Hash::make($input['password']);
            $input['add_by'] = auth()->user()?->id;
            unset($input['image']);
            $user = User::create($input);
            if($request->hasFile('image')){
                $user->changeUserImage($request->image);
            }
            $user->assignRole($request->input('roles'));
            return response()->json([
                "message" => "User Created",
                "status" => true,
                "redirect" => route('users.index'),
            ]);
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): View
    {
        $user = User::find($id);

        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id): View
    {
        $user = User::find($id);
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();
        $type = "Edit";
        $route = route('users.update', $id);
        return view('users.addEdit', compact('user', 'roles', 'userRole', 'type', 'route'));
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
        $error = $this->validateData([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'same:confirm-password',
            'roles' => 'required'
        ]);
        // if(!empty($error)){
        //     return $error;
        // }

        $input = $request->all();
        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, array('password'));
        }

        return $this->handleTransaction(function () use ($request, $input, $id) {
            $user = User::find($id);
            unset($input['image']);
            $user->update($input);
            if($request->hasFile('image')){
                $user->changeUserImage($request->image);
            }
            DB::table('model_has_roles')->where('model_id', $id)->delete();
            $user->assignRole($request->input('roles'));

            return response()->json([
                "message" => "User updated",
                "status" => true,
                "redirect" => route('users.index')
            ], 200);
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
            $user = User::find($id);
            $user->deleteImage();
            $user->delete();
            return response()->json([
                'status' => true,
                'message' => 'User deleted successfully',
            ]);
        });
    }
}
