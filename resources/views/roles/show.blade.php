@extends('layouts.master')
@php
    $pageTitle = 'Role Detail';
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

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div style="background-color: #d6eaf8;border-radius:10px;" class="p-2">

                                            <span><b>Role Name : </b> {{ $role->name }}</span>
                                            <hr class="m-0 my-1 border-secondary">
                                            @if($role?->CreateBy?->name)
                                            <span><b>Create By : </b> {{ $role?->CreateBy?->name }}</span>
                                            <hr class="m-0 my-1 border-secondary">
                                            @endif
                                            <span><b>Created At : </b> {{ $role->created_at }}</span>
                                            <hr class="m-0 my-1 border-secondary">


                                            <span><b>Updated At : </b> {{ $role->updated_at }}</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-9 col-md-6 col-sm-12 col-12">
                                        <div style="background-color: #d6eaf8;border-radius:10px;" class="p-2">
                                            <span><b>Permissions : </b></span>
                                            <div>
                                                @foreach ($rolePermissions as $permission)
                                                    <span class="badge "
                                                        style="background-color: #2471a3;font-weight:unset !important;color:#d6eaf8;font-size:14px;">{{ $permission->name }}</span>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <!-- /.card-body -->

                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection
