@extends('layouts.dash')
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

                   
                            <div class="col-sm-4">
                               <div class="element-box">
                                <h5 class="form-header">Add TrackerTrip</h5> 
								<form action="{{route('trackertrip.store')}}" method="POST" enctype="multipart/form-data" >
									{{ csrf_field() }}
									<div class="form-group">
                                   <input type="number" name="amount" class="form-control" data-error="" placeholder="amount" required="required" ><br>
                                   <input type="date" name="date" class="form-control" data-error="" placeholder="date" required="required" ><br>
                                   <select class="form-control" name="business_code">
                                   	@foreach($businesses as $business)
                                   	<option value="{{$business->unique_code}}">{{$business->name}}</option>
                                   	@endforeach
                                   </select>
								 	<br>
									 <center><button class="btn btn-sm btn-primary" name="submit">Add </button></center>
                                    </div>
								</form>
                            </div>
                             </div>
							 <div class="element-box col-lg-8">
							                     <div class="">
                                        <div class="table-responsive">
                                            <table id ="example" class="table table-lightborder">
                                                <thead>
                                                    <tr>
                                                        <th>Company Name</th>
                                                        <th class="text-center">Unique Code</th>
                                                        <th class="text-center">Amount</th>
                                                      <th class="text-right"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                        @foreach($trackertrips as $trackertrip)
                                                    <tr>
                                                        <td>
                                                            <div class="user-with-avatar"> <span style="font-size: 8px;"></span>  <span class="d-none d-xl-inline-block">{{$trackertrip->user['name']}}</span></div>
                                                        </td>
                                                        <td alt="safsfd" class="text-center">{{$trackertrip->business_code}}</td>
                                                         <td alt="safsfd" class="text-center">{{  $trackertrip->amount}}</td>
                                                        <td class="text-center">
                                                         <form action="" method="POST">
                                                           
                                                        </form>
                                                        </td>
                                                       
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