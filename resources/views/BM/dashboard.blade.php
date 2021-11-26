 @extends('layouts.bm')

@section('content')
 <div class="row">
                            <div class="col-sm-12">
                                <div class="element-wrapper">
                                    <h6 class="element-header">Dashboard</h6>
                                    <div class="element-content">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <a class="element-box el-tablo" href="#">
                                                    <div class="label">Total Region Drivers </div>
                                                    <div class="value">{{$r_driver_count}}</div>
													<div class="trending trending-up-basic"><span></span><!--<i class="os-icon os-icon-arrow-up2"></i> --></div>
                                                </a>
                                            </div>
                                            <div class="col-sm-4">
                                                <a class="element-box el-tablo" href="#"> 
                                                    <div class="label"> Trips Overview</div>
                                                    <div class="value">{{$tripCount}}</div>
													<div class="trending trending-up-basic"><span></span> </div>
                                                </a>
                                            </div>
                                            <div class="col-sm-4">
                                                <a class="element-box el-tablo" href="#">
                                                    <div class="label">Drivers in ALL Region</div>
                                                    <div class="value">{{$total_r_driver_count}}</div>
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
                                                    <div class="value">N{{number_format($tripAmt,2)}} </div>
                                                    <div class="trending trending-up-basic"><span></span><!--<i class="os-icon os-icon-arrow-up2"></i> --></div>
                                                </a>
                                            </div>
                                            <div class="col-sm-4">
                                                <a class="element-box el-tablo" href="#">
                                                    <div class="label">Today</div>
                                                    <div class="value">N{{number_format($tripAmtDay,2)}}</div>
                                                    <div class="trending trending-up-basic"><span></span> </div>
                                                </a>
                                            </div>
                                            <div class="col-sm-4">
                                                <a class="element-box el-tablo" href="#">
                                                    <div class="label">Today Trips Count</div>
                                                    <div class="value">{{$tripDayCount}} </div>
                                                    <div class="trending trending-up-basic"><span></span></div>
                                                </a>
                                            </div>
                                        </div>
                                      <!--   <div class="row">
                                            <div class="col-sm-4">
                                                <a class="element-box el-tablo" href="#">
                                                    <div class="label">Prev Month</div>
                                                    <div class="value"></div>
                                                    <div class="trending trending-up-basic"><span></span> </div>
                                                </a>
                                            </div>
                                            <div class="col-sm-4">
                                                <a class="element-box el-tablo" href="#">
                                                    <div class="label">This Year</div>
                                                    <div class="value"> </div>
                                                    <div class="trending trending-up-basic"><span></span> </div>
                                                </a>
                                            </div>
                                             <div class="col-sm-4">
                                                <a class="element-box el-tablo" href="#">
                                                    <div class="label">This Week</div>
                                                    <div class="value"> </div>
                                                    <div class="trending trending-up-basic"><span></span> </div>
                                                </a>
                                            </div>
                                            
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                        </div>
						<div class="row">
                            <div class="col-sm-12">
                   
                            </div>
						 </div>  

@endsection