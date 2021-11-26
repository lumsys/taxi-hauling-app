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

                   
                            <div class="col-sm-6">
                               <div class="element-box">
                                <h5 class="form-header">Edit Branch</h5> 
								<form action="{{route('business.branch.posteditB',$branch->id)}}" method="POST" enctype="multipart/form-data" >
									{{ csrf_field() }}
									<div class="form-group">
                                   <input type="text" name="name" class="form-control" data-error="" placeholder="Fullname" required="required" value="{{$branch->name}}" ><br>
								 	<input type="email"  class="form-control" name="email" placeholder="Email" required="required" value="{{$branch->email}}"><br>
									<input type="text" name="phone" class="form-control" required="required" placeholder="phone" value="{{$branch->phone}}"><br>
								<!--	<input type="text" name="password" class="form-control"  required="required" placeholder="password"><br>
									<label>Profile Pix</label>
									<input type ="file" name="profileUrl" class="form-control"  placeholder="image" ><br> -->
	 						<center><button class="btn btn-sm btn-primary" name="submit">Edit Branch</button></center>
                                    </div>
								</form>
                            </div>
                             </div>
							                                       
                                             
                             </div>
                            </div>
                            
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
