@extends('layouts.dash')

@section('content')

<div class="element-wrapper">
                            <div class="user-profile">
                                <div class="up-head-w" style="background-image:url(first/img/profile_bg1.jpg)">
                            <!--        <div class="up-social"><a href="#"><i class="os-icon os-icon-twitter"></i></a><a href="#"><i class="os-icon os-icon-facebook"></i></a></div> -->
                                    <div class="up-main-info">
                                        <div class="user-avatar-w">
                                            <div class="user-avatar"><img alt="" src="first/img/avatar.jpg"></div>
                                        </div>
                                        <h1 class="up-header">{{$user->name}}</h1>
                                        <h5 class="up-sub-header">{{$user->email}}| {{$user->phone}}</h5></div>
                                    <svg class="decor" width="842px" height="219px" viewBox="0 0 842 219" preserveAspectRatio="xMaxYMax meet" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                        <g transform="translate(-381.000000, -362.000000)" fill="#FFFFFF">
                                            <path class="decor-path" d="M1223,362 L1223,581 L381,581 C868.912802,575.666667 1149.57947,502.666667 1223,362 Z"></path>
                                        </g>
                                    </svg>
                                </div>
                                <div class="up-controls">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="value-pair">
                                                <div class="label">Status:</div>
                                                <div class="value badge badge-pill badge-success">Online </div>
                                            </div>
                                            <div class="value-pair">
                                                <div class="label">Member Since:</div>
                                                <div class="value">{{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $user->created_at)->year}}</div>
                                            </div>
                                        </div>
                                         
										</div>
                                </div>
                            </div>
                        </div>

@endsection