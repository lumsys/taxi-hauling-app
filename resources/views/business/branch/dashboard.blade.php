 @extends('layouts.business')

@section('content')

<div class="element-wrapper">
                    @if (session('message'))
                        <div class="alert alert-success">
                            {{ session('message') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
  <div class="row">
                            <div class="col-sm-12">
                                <div class="element-wrapper">
                                    <h6 class="element-header">Dashboard</h6>
                                    <div class="element-content">
                                        <div class="row">
                                       <!--     <div class="col-sm-4">
                                                <a class="element-box el-tablo" href="#">
                                                    <div class="label">Wallet</div>
                                                    <div class="value">$500</div>
                                                    <div class="trending trending-up-basic"><span>12%</span><i class="os-icon os-icon-arrow-up2"></i></div>
                                                </a>
                                            </div> -->
                                            <div class="col-sm-4">
                                                <a class="element-box el-tablo" href="#">
                                                    <div class="label">Trips Overview</div>
                                                    <div class="value">{{$tripCount}}</div>
                                                    <div class="trending trending-up-basic"><span></span> </div>
                                                </a>
                                            </div>
                                            <div class="col-sm-4">
                                                <a class="element-box el-tablo" href="#">
                                                    <div class="label">Users</div>
                                                    <div class="value">{{$userCount}}</div>
                                                    <div class="trending trending-up-basic"><span></span></div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                     <div class="row">
                            <div class="col-sm-12">
                                <div class="element-wrapper">
                                     
                                    <div class="element-content">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <a class="element-box el-tablo" href="#">
                                                    <div class="label">Total Amount</div>
                                                    <div class="value">N{{number_format($totaltripAmt,2)}}</div>
                                                    <div class="trending trending-up-basic"><span></span><!--<i class="os-icon os-icon-arrow-up2"></i> --></div>
                                                </a>
                                            </div>
                                            <div class="col-sm-4">
                                                <a class="element-box el-tablo" href="#">
                                                    <div class="label">Today</div>
                                                    <div class="value">N{{number_format($totaltripAmtDay,2)}}</div>
                                                    <div class="trending trending-up-basic"><span></span> </div>
                                                </a>
                                            </div>
                                            <div class="col-sm-4">
                                                <a class="element-box el-tablo" href="#">
                                                    <div class="label">This Month</div>
                                                    <div class="value">N{{number_format($totaltripAmtMonth,2)}}</div>
                                                    <div class="trending trending-up-basic"><span></span></div>
                                                </a>
                                            </div>
                                        </div>
                                         <div class="row">
                                            <div class="col-sm-4">
                                                <a class="element-box el-tablo" href="#">
                                                    <div class="label">Prev Month</div>
                                                    <div class="value">N{{number_format($totaltripAmtMonthPrev,2)}}</div>
                                                    <div class="trending trending-up-basic"><span></span><!--<i class="os-icon os-icon-arrow-up2"></i> --></div>
                                                </a>
                                            </div>
                                            <div class="col-sm-4">
                                                <a class="element-box el-tablo" href="#">
                                                    <div class="label">This Year</div>
                                                    <div class="value">N{{number_format($totaltripAmtYear,2)}}</div>
                                                    <div class="trending trending-up-basic"><span></span> </div>
                                                </a>
                                            </div>
                                             <div class="col-sm-4">
                                                <a class="element-box el-tablo" href="#">
                                                    <div class="label">This Week</div>
                                                    <div class="value">N{{number_format($totaltripAmtWeek,2)}}</div>
                                                    <div class="trending trending-up-basic"><span></span> </div>
                                                </a>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                          <div class="row">
                            <div class="col-sm-7">
                                <div class="element-wrapper">
                        <!--            <div class="element-box">
                                        <div class="os-tabs-w">
                                            <div class="os-tabs-controls">
                                                <ul class="nav nav-tabs smaller">
                                                    <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#tab_overview">Overview</a></li>
                                                </ul>
                                                <ul class="nav nav-pills smaller d-none d-md-flex">
                                                    <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#">Today</a></li>
                                                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#">7 Days</a></li>
                                                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#">Last Month</a></li>
                                                </ul>
                                            </div>
                                            <div class="tab-content">
                                                <div class="tab-pane active" id="tab_overview">
                                                    <div class="el-chart-w">
                                                        <canvas height="150px" id="lineChart" width="600px"></canvas>
                                                    </div>
                                                </div>
                                                <div class="tab-pane" id="tab_sales"></div>
                                            </div>
                                        </div>
                                    </div> -->
                                </div>
                            </div>
                    <div class="col-lg-5 col-xxl-5">
    <!--START - Money Withdraw Form
    <div class="element-wrapper">
        <div class="element-box">
            <form>
                <h5 class="element-box-header">Fund Wallet</h5>
                <div class="row">
                    <div class="col-sm-8">
                        <div class="form-group">
                            <label class="lighter" for="">Enter Amount</label>
                            <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                                <input class="form-control" placeholder="Enter Amount" value="" type="text">
                                <div class="input-group-append">
                                    <div class="input-group-text">&#8358;</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="lighter" for="">Type</label>
                            <select class="form-control">
                                <option value="">Card</option>
                                <option value="">Voucher</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-buttons-w text-right compact"><a class="btn btn-primary" href="#"><span>Fund</span><i class="os-icon os-icon-grid-18"></i></a></div>
            </form>
        </div>
    </div> -->
    <!--END - Money Withdraw Form-->
</div>   </div>
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