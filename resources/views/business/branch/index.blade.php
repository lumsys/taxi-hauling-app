 @extends('layouts.business')
 @section('css')
  <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/buttons/1.4.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />

@endsection

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

                   
                            <div class="col-sm-3">
                               <div class="element-box">
                                <h5 class="form-header">Add Branch</h5> 
								<form action="{{route('branch.store')}}" method="POST" enctype="multipart/form-data" >
									{{ csrf_field() }}
									<div class="form-group">
                                   <input type="text" name="name" class="form-control" data-error="" placeholder="Fullname" required="required" ><br>
								 	<input type="email"  class="form-control" name="email" placeholder="Email" required="required" ><br>
									<input type="text" name="phone" class="form-control" required="required" placeholder="phone" ><br>
									<input type="text" name="password" class="form-control"  required="required" placeholder="password"><br>
								<!--	<label>Profile Pix</label>
									<input type ="file" name="profileUrl" class="form-control"  placeholder="image" ><br> -->
	 
												<center><button class="btn btn-sm btn-primary" name="submit">Add Branch</button></center>
                                    </div>
								</form>
                            </div>
                             </div>
							 <div class="element-box col-lg-9">
							                     <div class="">
                                        <div  style="overflow-x: scroll; width: 90%" class="table-responsive">
                                            <table id ="example" class="table table-lightborder">
                                                <thead>
                                                    <tr>
                                                        <th>Branch Name</th>
                                                        <th class="text-center">Email</th>
                                                        <th class="text-center">Phone</th>
                                                        <th class="text-center">View</th>
                                                        <th class="text-center">Edit</th>
                                                        <th class="text-right">Status</th>
                                                                                                            </tr>
                                                </thead>
                                                <tbody>
                            
                                                 @foreach($branches as $branch)
                                                    <tr>
                                                        <td>
                                                            <div class="user-with-avatar"><span class="d-none d-xl-inline-block">{{$branch->name}}</span></div>
                                                        </td>
                                                        <td class="text-center">{{$branch->email}}</td>
                                                        <td class="text-center">{{$branch->phone}}</td>
                                                        <td class="text-center"><a class="btn btn-sm btn-success" href="{{route('business.businessbranchdashboard', $branch->unique_code)}}" target="_blank">view</a></td>
                                                        <td class="text-center"><a class="btn btn-sm btn-success" href="{{route('business.branch.editB', $branch->unique_code)}}" target="_blank">Edit</a></td>
                                                        <td class="text-right"><a class="btn btn-sm btn-danger" href="" onclick="return confirm('Are you sure you want to delete this?')">Delete</a></td>
                                                    </tr>
                                                 @endforeach  
                                                   
                                                </tbody>
                                            </table>
											<div class="controls-below-table">
                                                 
                                                  
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

 
@section('script')
 <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.colVis.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $('#example').DataTable( {
                dom: 'Bfrtip',
                buttons: [
            
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2,3,4, 5,6,7,8,9,10,11,12]
                }
            },
        ]
            } );
        } );
    </script>
@endsection