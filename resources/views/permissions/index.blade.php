@extends('layouts.master')
@php
    $pageTitle = 'Permissions';
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
                    <div class="col-lg-12 col-12">

                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h3 class="card-title">List</h3>
                                    @can('permission-create')
                                        <button class="btn btn-primary btn-sm modalOpen" data-id="0"
                                            data-modal-url="{{ route('permissions.create') }}"><i class="fas fa-plus"></i>
                                            Permission</button>
                                    @endcan
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="dataTable" class="table table-bordered table-striped table-hover ajaxDataTable">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Add By</th>
                                            <th>Created At</th>
                                            @hasAnyPermission('permission-edit', 'permission-delete')

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
                                            <th>Add By</th>
                                            <th>Created At</th>
                                            @hasAnyPermission('permission-edit', 'permission-delete')
                                                <th>Action</th>
                                            @endhasAnyPermission
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                        <!-- /.content -->
                    </div>
                </div>
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
                    searchable: true,
                    render: (data, display, row) => {
                        if (row.description)
                            return `<span title="${row.description}">${data}</span>`;
                        return data;
                    }
                },
                {
                    data: 'addByName',
                    name: 'addBy.name',
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                @hasAnyPermission('permission-edit', 'permission-delete') {
                    data: 'action',
                    name: 'action',
                    searchable: false,
                    orderable: false
                }
                @endhasAnyPermission
            ];
            let table = initDataTable("dataTable", '{{ route('permissions.list') }}', columns);

        })
    </script>
@endpush
