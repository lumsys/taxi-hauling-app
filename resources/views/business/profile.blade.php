@extends('layouts.business')

@section('content')

<div class="element-wrapper">
                    @if (session('message'))
                        <div class="alert alert-success">
                            {{ session('message') }}
                        </div>
                    @endif
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
                                               <div class="value">{{  \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $user->created_at)->year }} </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="value-pair">
                                                <div class="label">AutoApprove:</div>
                                                <div class="value badge badge-pill badge-success">{{   $user->autoApprove ? 'Yes' : 'No' }} </div>
                                            </div>
                                            <div class="value-pair">
                                                <form action="{{route('business.autoapprovebusiness')}}" method="POST">
                                                <div class="label">                                
                                                            {{ csrf_field() }}
                                                            <select name="autoApprove" class="form-control">
                                                                <option value="1">Yes</option>
                                                                <option value="0">No</option>
                                                            </select> 
                                            </div>
                                             <div class="value ">  <button type="submit" class="btn btn-default">Submit</button></div>
                                            </div>
                                             </form>
                                        </div>
                                         
										</div>
                                </div>
                            </div>
                        </div>

@endsection


@section('script')

  <script src="{{asset('first/bower_components/jquery/dist/jquery.min.js')}}"></script>
   <script src="{{asset('first/bower_components/popper.js/dist/umd/popper.min.js')}}"></script>
    <script src="{{asset('first/bower_components/moment/moment.js')}}"></script>
    <script src="{{asset('first/bower_components/chart.js/dist/Chart.min.js')}}"></script>
    <script src="{{asset('first/bower_components/select2/dist/js/select2.full.min.js')}}"></script>
    <script src="{{asset('first/bower_components/jquery-bar-rating/dist/jquery.barrating.min.js')}}"></script>
    <script src="{{asset('first/bower_components/ckeditor/ckeditor.js')}}"></script>
    <script src="{{asset('first/bower_components/bootstrap-validator/dist/validator.min.js')}}"></script>
    <script src="{{asset('first/bower_components/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
    <script src="{{asset('first/bower_components/ion.rangeSlider/js/ion.rangeSlider.min.js')}}"></script>
    <script src="{{asset('first/bower_components/dropzone/dist/dropzone.js')}}"></script>
     <script src="{{asset('first/bower_components/editable-table/mindmup-editabletable.js')}}"></script>
    <script src="{{asset('first/bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('first/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
    <script src="{{asset('first/bower_components/fullcalendar/dist/fullcalendar.min.js')}}"></script>
    <script src="{{asset('first/bower_components/perfect-scrollbar/js/perfect-scrollbar.jquery.min.js')}}"></script>
    <script src="{{asset('first/bower_components/tether/dist/js/tether.min.js')}}"></script>
    <script src="{{asset('first/bower_components/slick-carousel/slick/slick.min.js')}}"></script>
    <script src="{{asset('first/bower_components/bootstrap/js/dist/util.js')}}"></script>
    <script src="{{asset('first/bower_components/bootstrap/js/dist/alert.js')}}"></script>
    <script src="{{asset('first/bower_components/bootstrap/js/dist/button.js')}}"></script>
    <script src="{{asset('first/bower_components/bootstrap/js/dist/carousel.js')}}"></script>
    <script src="{{asset('first/bower_components/bootstrap/js/dist/collapse.js')}}"></script>
    <script src="{{asset('first/bower_components/bootstrap/js/dist/dropdown.js')}}"></script>
    <script src="{{asset('first/bower_components/bootstrap/js/dist/modal.js')}}"></script>
    <script src="{{asset('first/bower_components/bootstrap/js/dist/tab.js')}}"></script>
    <script src="{{asset('first/bower_components/bootstrap/js/dist/tooltip.js')}}"></script>
    <script src="{{asset('first/bower_components/bootstrap/js/dist/popover.js')}}"></script>
    <script src="{{asset('first/js/demo_customizer4a76.js?version=4.3.0')}}"></script>
    <script src="{{asset('first/js/main4a76.js?version=4.3.0')}}"></script>
@endsection