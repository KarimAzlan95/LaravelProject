@extends('master')
@section('content')
    <div class="md-3">
        <div class="card text-center">
            <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill"
                           href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home"
                           aria-selected="true">Categories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill"
                           href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile"
                           aria-selected="false">Products</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="custom-tabs-one-tabContent">
                    <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel"
                         aria-labelledby="custom-tabs-one-home-tab">
                        @livewire('inventory.category')
                    </div>
                    <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel"
                         aria-labelledby="custom-tabs-one-profile-tab">
                        @livewire('inventory.product')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
