@extends('layouts.business')

@section('content')


<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

 
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

<!--
                    <div class="row">

                   
                            <div class="col-sm-3">
                               <div class="element-box">
                                <h5 class="form-header">Add Staff</h5> 
								<form action="{{route('business.poststaff')}}" method="POST" enctype="multipart/form-data" >
									{{ csrf_field() }}
									<div class="form-group">
                                   <input type="text" name="name" class="form-control" data-error="" placeholder="Fullname" required="required" ><br>
								 	<input type="email"  class="form-control" name="email" placeholder="Email" required="required" ><br>
									<input type="text" name="phone" class="form-control" required="required" placeholder="phone" ><br>
									<input type="text" name="password" class="form-control"  required="required" placeholder="password"><br>
								 	<label>Company/Branch</label>
                                    <select name="business_code" class="form-control">
                                         <option value="{{Auth::user()->unique_code}}"> {{ Auth::user()->name }}</option>
                                        @foreach($businesses as $business)
                                        <option value="{{$business->unique_code}}">{{$business->name}}</option> 
                                        @endforeach
                                    </select> <br>
									<input type ="file" name="profileUrl"   placeholder="image" ><br> 
	 
												<center><button class="btn btn-sm btn-primary" name="submit">Add Staff</button></center>
                                    </div>

      
								</form>
                            </div>
                             </div>

  !-->









      <!-- Button trigger modal -->
      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" style: >
Add Staff
</button>
<br>
<br>

<!--Start Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="false">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Staff</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form action="{{route('business.poststaff')}}" method="POST" enctype="multipart/form-data" >
{{csrf_field()}}


      <div class="modal-body">
     
  <div class="form-row">

  <div class="form-group col-md-6">
  <label for="inputEmail4">Full Name</label>
     <input type="text" name="name" class="form-control" data-error="" placeholder="Fullname" required="required" ><br>
     </div>
    <div class="form-group col-md-6">
      <label for="inputEmail4">Email</label>
      <input type="email"  class="form-control" name="email" placeholder="Email" required="required" ><br>
    </div>

    <div class="form-group">
    <label for="inputAddress">Phone Number</label>
    <input type="text" name="phone" class="form-control" required="required" placeholder="phone" ><br>
  </div>

    <div class="form-group col-md-6">
      <label for="inputPassword4">Password</label>
      <input type="text" name="password" class="form-control"  required="required" placeholder="password"><br>
    </div>
  </div>
<div>
  <label>Company/Branch</label>
                                    <select name="business_code" class="form-control">
                                         <option value="{{Auth::user()->unique_code}}"> {{ Auth::user()->name }}</option>
                                        @foreach($businesses as $business)
                                        <option value="{{$business->unique_code}}">{{$business->name}}</option> 
                                        @endforeach
                                    </select> <br>
  
</div>
  <div class="form-row">
    <!--
    <div class="form-group col-md-4">
      <label for="inputState">State</label>
      <select id="inputState" class="form-control">
        <option selected>Choose...</option>
        <option>...</option>
      </select>
    </div>

    !-->
    <div class="form-group">
    <label>Profile Pix</label>
        <input type ="file" name="profileUrl" class="form-control"  placeholder="image" ><br>
  </div>
 
   
  </div>
  <center><button class="btn btn-sm btn-primary" name="submit">Add Staff</button></center>



      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
       
      </div>
      </form>

    </div>
  </div>
</div>
</div>
    <!--End Modal -->













							 <div class="element-box col-lg-30">
							                     <div class="">
                                        <div class="table-responsive">
                                            <table class="table table-darkborder table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Staff Name</th>
                                                        <th class="text-center">Email</th>
                                                        <th class="text-center">Phone</th>
                                                        <th class="text-right">Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                       @foreach($staffs as $staff)
                                                    <tr>
                                                        <td>
                                                            <div class="user-with-avatar"><span class="d-none d-xl-inline-block">{{$staff->name}}</span></div>
                                                        </td>
                                                        <td class="text-center">{{$staff->email}}</td>
                                                        <td class="text-center">{{$staff->phone}}</td>
                                                        <td class="text-right"><a class="btn btn-sm btn-danger" href="{{route('business.deletestaff',$staff->id)}}" onclick="return confirm('Are you sure you want to delete this?')">Delete</a></td>
                                                    </tr>
                                    @endforeach     
                                                    
                                                </tbody>
                                            </table>
											<div class="controls-below-table">
                                                {{$staffs->links()}}
                                                 
                            <!--                <div class="table-records-info">Showing records 1 - 6</div>
                                            <div class="table-records-pages">
                                                <ul>
                                                    <li><a href="#">Previous</a></li>
                                                    <li><a class="current" href="#">1</a></li>
                                                    <li><a href="#">2</a></li>
                                                    <li><a href="#">3</a></li>
                                                    <li><a href="#">4</a></li>
                                                    <li><a href="#">Next</a></li>
                                                </ul>
                                            </div> -->
                                        </div>
                                        </div>
                                    </div>
                             </div>
                            </div></div>
@endsection

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>


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