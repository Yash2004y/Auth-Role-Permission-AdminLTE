@extends('layouts.master')
@php
    $pageTitle = 'Users';
    $breadCrumb = [['title' => 'Dashboard', 'url' => route('home')], ['title' => $pageTitle]];
@endphp
@section('title', $pageTitle)
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">{{ $pageTitle }}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <x-breadcrumb :items=$breadCrumb />
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h3 class="card-title">List</h3>
                                    @can('user-create')

                                    <a class="btn btn-primary btn-sm" href="{{ route('users.create') }}"><i
                                        class="fas fa-plus"></i>
                                        User</a>
                                    @endcan
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                 <div class="row mb-3">
                                    <div class="col-lg-11 col-md-10 col-sm-9 col-9 p-0">
                                        <select class="select2 form-control roles">
                                            <option value="">Select role</option>
                                            @foreach ($roles as $r)
                                                <option value="{{ $r->id }}">{{ $r->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div
                                        class="col-lg-1 col-md-2 col-sm-3 col-3 p-0 d-flex justify-content-center align-items-center">
                                        <div>
                                            <button class="btn btn-primary btn-sm filter_button"><i
                                                    class="fas fa-filter"></i></button>
                                            <button class="btn btn-danger btn-sm reset_button"><i
                                                    class="fas fa-times"></i></button>

                                        </div>
                                    </div>
                                </div>
                                <table id="dataTable" class="table table-bordered table-striped table-hover ajaxDataTable">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Image</th>
                                            <th>Roles</th>
                                            <th>Email</th>
                                            <th>Add By</th>
                                            <th>Created At</th>
                                        @hasAnyPermission('user-delete','user-edit')

                                            <th>Action</th>
                                        @endhasAnyPermission
                                        </tr>
                                    </thead>
                                    <tbody>


                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Image</th>
                                            <th>Roles</th>
                                            <th>Email</th>
                                            <th>Add By</th>
                                            <th>Created At</th>
                                        @hasAnyPermission('user-delete','user-edit')

                                            <th>Action</th>
                                            @endhasAnyPermission

                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>

                    </div>
                </div>

                <!-- /.card -->

            </div>
        </section>
    </div>
@endsection
@push('bottom-script')
    <script>
        $(document).ready(function() {
            const columns = [{
                    data: 'id',
                    name: 'id',
                    searchable: true
                },
                {
                    data: 'name',
                    name: 'name',
                    searchable: true
                },
                {
                    data: 'image',
                    name: 'image',
                    searchable: false,
                    orderable:false,
                },
                {
                    data: 'roles',
                    name: 'roles',
                    searchable: true,
                    orderable:false,
                    render:(data,display,row)=>{
                        html = `<div>`;
                        data.forEach((item,index)=>{
                            html += `<span class="badge bg-primary">${item.name}</span>`
                        });
                        html += `</div>`;
                        return html;
                    }
                },
                {
                    data: 'email',
                    name: 'email',
                    searchable: true
                },
                {
                    data: 'addByName',
                    name: 'addBy.name',
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                @hasAnyPermission('user-delete','user-edit')

                {
                    data: 'action',
                    name: 'action',
                    searchable: false,
                    orderable: false
                }
                @endcan
            ];
            let table = initDataTable("dataTable", '{{ route('users.list') }}', columns,()=>{
                return {
                    role_id:$('.roles').val()
                };
            });
            $(".filter_button").click(function() {
                console.log($(".roles").val());
                table.draw();
            })
            $(".reset_button").click(function() {
                $(".roles").val("").trigger("change.select2");
                table.draw();
            })

        })
    </script>
@endpush
