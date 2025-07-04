@extends('layouts.master')
@php
    $pageTitle = 'Blank Page';
    $breadCrumb = [['title' => $pageTitle]];
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

                {{-- content --}}

            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection
