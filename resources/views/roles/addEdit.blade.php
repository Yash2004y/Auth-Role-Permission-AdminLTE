@extends('layouts.master')
@php
    $pageTitle = 'Role ' . $type;
    $breadCrumb = [
        ['title' => 'Dashboard', 'url' => route('home')],
        ['title' => 'Roles', 'url' => route('roles.index')],
        ['title' => $pageTitle],
    ];
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

                        {{-- content --}}
                        <!-- general form elements -->
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Details</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form class="ajaxForm" method="POST" action="{{ $route }}">
                                @if ($type == 'Edit')
                                    @method('PUT')
                                @endif
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="name">Role Name</label>
                                        <input type="name" class="form-control" id="name"
                                            value="{{ old('name', $role?->name ?? '') }}" name="name"
                                            placeholder="Enter name">
                                        <small class="text-danger error-common name-error"></small>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea type="description" class="form-control" id="description" name="description" placeholder="Enter description">{{ old('description', $role?->description ?? '') }}</textarea>
                                        <small class="text-danger error-common description-error"></small>
                                    </div>

                                    <div class="card card-info mt-3">
                                        <div class="card-header p-2">
                                            <h4 class="card-title m-0">Permissions</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-check icheck-primary">
                                                        <input class="form-check-input" type="checkbox" id="selectAll"
                                                            {{ count($rolePermissions) == count($permission) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="selectAll">
                                                            Select All Permission
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                @foreach ($permission as $p)
                                                    <div class="col-lg-3 col-md-6 col-sm-12 mb-2">
                                                        <div class="form-check icheck-primary">
                                                            <input class="form-check-input permission-checkbox"
                                                                type="checkbox" name="permission[]"
                                                                id="permission_{{ $p->id }}"
                                                                value="{{ $p->id }}"
                                                                {{ in_array($p->id, old('permission', $rolePermissions ?? [])) ? 'checked' : '' }}>
                                                            <label class="form-check-label"
                                                                for="permission_{{ $p->id }}">
                                                                {{ $p->name }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <small class="text-danger error-common permission-error"></small>



                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection
@push('bottom-script')
    <script>
        $('.select2').select2()
        let TotalAvailablePermission = @json(count($permission));
        $(document).ready(function() {
            $("#selectAll").change(function(e) {
                $(".permission-checkbox").prop('checked', $(this).prop('checked'));
            })
            $(".permission-checkbox").change(function(e) {
                var totalSelected = $(".permission-checkbox:checked").length;
                if (TotalAvailablePermission != totalSelected) {
                    $("#selectAll").prop('checked', false);
                }
                if (TotalAvailablePermission == totalSelected) {
                    $("#selectAll").prop('checked', true);

                }
            })
        })
    </script>
@endpush
