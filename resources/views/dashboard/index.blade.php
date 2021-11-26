 @extends('layouts.dash')

@section('content')
 <div class="row">
                            <div class="col-sm-12">
                                <div class="element-wrapper">
                                    <h6 class="element-header">Dashboard</h6>
                                    <div class="element-content">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <a class="element-box el-tablo" href="#">
                                                    <div class="label">Business Account</div>
                                                    <div class="value">{{$business}}</div>
													<div class="trending trending-up-basic"><span></span><!--<i class="os-icon os-icon-arrow-up2"></i> --></div>
                                                </a>
                                            </div>
                                            <div class="col-sm-4">
                                                <a class="element-box el-tablo" href="#">
                                                    <div class="label">Trips Overview</div>
                                                    <div class="value">{{$trip}}</div>
													<div class="trending trending-up-basic"><span></span> </div>
                                                </a>
                                            </div>
                                            <div class="col-sm-4">
                                                <a class="element-box el-tablo" href="#">
                                                    <div class="label">Drivers</div>
                                                    <div class="value">{{$driver}}</div>
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
                            <div class="col-sm-12">
                    <!--            <div class="element-wrapper">
                                    <div class="element-box">
                                        <div class="os-tabs-w">
                                            <div class="os-tabs-controls">
                                                <ul class="nav nav-tabs smaller">
                                                    <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#tab_overview">Overview</a></li>
                                                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab_sales">Trips</a></li>
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
                                                <div class="tab-pane" id="tab_conversion"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->
                            </div>
						 </div>

@endsection