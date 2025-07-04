@extends('layouts.master')
@php
    $pageTitle = 'User ' . $type;
    $breadCrumb = [
        ['title' => 'Dashboard', 'url' => route('home')],
        ['title' => 'Users', 'url' => route('users.index')],
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
                    <div class="col-12 col-lg-12">

                        {{-- content --}}
                        <!-- general form elements -->
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Details</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form class="ajaxForm" enctype="multipart/form-data" method="POST" action="{{ $route }}">
                                @if ($type == 'Edit')
                                    @method('PUT')
                                @endif
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input class="form-control" id="name" placeholder="Enter name"
                                            value="{{ old('name', $user?->name ?? '') }}" name="name">
                                        <small class="text-danger error-common name-error"></small>
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email address</label>
                                        <input type="email" class="form-control" id="email"
                                            value="{{ old('name', $user?->email ?? '') }}" name="email"
                                            placeholder="Enter email">
                                        <small class="text-danger error-common email-error"></small>

                                    </div>
                                    <div class="form-group mb-2">
                                        <label for="image">Image</label>
                                        <div class="row align-items-center">
                                            <!-- Image Preview -->
                                            <div class="col-lg-2 col-md-3 col-sm-4 col-12 mb-2 mb-sm-0 text-center">
                                                <img id="previewImage" src="{{ $user?->image ?? asset('ProjectImages/placeholder.png') }}"
                                                    class="img img-thumbnail object-fit-contain "
                                                    style="width: 100px; height: 100px;" alt="Preview">
                                            </div>

                                            <!-- Image Input -->
                                            <div class="col-lg-10 col-md-9 col-sm-8 col-12">
                                                <input type="file" data-preview-target="previewImage" class="form-control image-with-preview-input" id="image" name="image">
                                                <small class="text-danger error-common image-error"></small>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group mb-2">
                                        <label>Role</label>
                                        <select class="select2" name="roles[]" style="width: 100%;">
                                            <option value="">Select role</option>
                                            @foreach ($roles as $r)
                                                <option value="{{ $r }}"
                                                    {{ in_array($r, $userRole ?? old('roles', [])) ? 'selected' : '' }}>
                                                    {{ $r }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" class="form-control" name="password" id="password"
                                            placeholder="Password">
                                        <small class="text-danger error-common password-error"></small>

                                    </div>

                                    <div class="form-group">
                                        <label for="confirm-password">Confirm Password</label>
                                        <input type="password" class="form-control" id="confirm-password"
                                            name="confirm-password" placeholder="Confirm password">
                                        <small class="text-danger error-common confirm-password-error"></small>

                                    </div>

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
    </script>
@endpush
