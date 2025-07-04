@extends('layouts.master')
@section('title', 'Dashboard')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Dashboard</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <x-breadcrumb :items="[['title' => 'Dashboard']]" />
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                @php
                    $colors = ['bg-info', 'bg-success', 'bg-warning', 'bg-danger'];
                @endphp
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    @foreach ($counts as $index => $c)
                        <div class="col-lg-3 col-6">
                            <!-- small box -->
                            <div class="small-box {{ $colors[$index % count($colors)] }}">
                                <div class="inner">
                                    <h3>{{$c['count'] ?? 0}}</h3>

                                    <p>{{$c['title'] ?? ''}}</p>
                                </div>
                                <div class="icon">
                                    <i class="{{$c['icon'] ?? ''}}" style="font-size: 70px"></i>
                                    {{-- <i class="ion ion-bag"></i> --}}
                                </div>
                                <a href="{{$c['redirect']}}" class="small-box-footer">More info <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    @endforeach
                    <!-- ./col -->

                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection
