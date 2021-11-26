@extends('layouts.dash')

@section('title')
All Business Account
@endsection
 
@section('content')
<div class="element-wrapper">
                            <div class="row">
                                    <div class="col-lg-12 col-xxl-12">
                                        <div class="element-content">
                                        <div class="row">
                            @foreach($businesses as $business)
                                <div class="col-sm-3">
                                    <div class="profile-tile">
                                    <div class="profile-tile-meta profile-tile-box">
                                        <a href="#" class="">
                                    <!--    <div class="pt-avatar-w"><img alt="" src="img/avatar1.jpg"></div> -->
                                        <div class="pt-user-name">{{$business->name}}</div>
                                    </a><ul>
                                            <li>Phone:<strong>{{$business->phone}}</strong></li>
                                            <li>Email:<strong>{{$business->email}}</strong></li>
                                            <li>Status:<strong>Verified</strong></li>
                                        </ul>
                                        <div class="pt-btn"><a class="btn btn-success btn-sm" href="{{route('dashboard.getbusinesstrips', ['company' => $business->company, 'businesscode' => $business->unique_code])}}">View Account Trips</a></div>
                                       
                                        <div class="pt-btn"><a class="btn btn-success btn-sm" href="{{route('dashboard.getbusinessstaffs', ['company' => $business->company, 'businesscode' => $business->unique_code])}}">View Staff</a></div>
                                        @if($business->largeData == 0)
                                            <div class="pt-btn"><a class="btn btn-primary btn-sm" href="{{route('setLargeData', ['id' => $business->id])}}">Set Large data</a></div>
                                        @else
                                            <div class="pt-btn"><a class="btn btn-danger btn-sm" href="{{route('removeLargeData', ['id' => $business->id])}}">Remove Large data</a></div>
                                        @endif
                                    </div>
                                </div>
                                  </div>  
                            @endforeach

                                  </div>

                                </div>
                                </div>
                               </div>    
                           </div>

@endsection
