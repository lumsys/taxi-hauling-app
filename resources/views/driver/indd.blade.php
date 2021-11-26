@extends('layouts.dash')
@section('css')
  <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/buttons/1.4.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

@endsection
@section('content')

<div class="element-wrapper">
    @if (\Session::has('success'))
        <div class="alert alert-success prompt">
            <div class="text-right text-danger"><i class="fas fa-times fa-2x" id="close"></i></div>
            {!! \Session::get('success') !!}
            
            
        </div>
    @endif
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

                   
                            <div class="col-sm-4">
                               <div class="element-box">
                                <h5 class="form-header">Add Driver</h5> 
                                <form action="{{route('driver.store')}}" method="POST" enctype="multipart/form-data" >
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                   <input type="text" name="name" class="form-control" data-error="" placeholder="Fullname" required="required" ><br>
                                    <input type="email"  class="form-control" name="email" placeholder="Email" required="required" ><br>
                                    <input type="text" name="phone" class="form-control" required="required" placeholder="phone" ><br>
                                    <input type="text" name="password" class="form-control"  required="required" placeholder="password"><br>
                                    <label>Profile Pix</label>
                                    <input type ="file" name="profileUrl" class="form-control"  placeholder="image" ><br>
     
                                                <center><button class="btn btn-sm btn-primary" name="submit">Add Driver</button></center>
                                    </div>
                                </form>
                            </div>
                             </div>
                           
      !-->


      <!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" style: >
Add a Driver
</button>
<br>
<br>

<!--Start Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="false">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Driver</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form action="{{route('driver.store')}}" method="POST" enctype="multipart/form-data" >

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
    <div class="form-group col-md-6">
      <label for="inputPassword4">Password</label>
      <input type="text" name="password" class="form-control"  required="required" placeholder="password"><br>
    </div>
  </div>
  <div class="form-group">
    <label for="inputAddress">Phone Number</label>
    <input type="text" name="phone" class="form-control" required="required" placeholder="phone" ><br>
  </div>
  
  <div class="form-row">
    
    <div class="form-group col-md-4">
      <label for="inputState">State</label>
      <select id="inputState" class="form-control">
        <option selected>Choose...</option>
        <option>...</option>
      </select>
    </div>
    <div class="form-group">
    <label>Profile Pix</label>
        <input type ="file" name="profileUrl" class="form-control"  placeholder="image" ><br>
  </div>
 
   
  </div>
  <center><button class="btn btn-sm btn-primary" name="submit">Add Driver</button></center>



      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
       
      </div>
      </form>

    </div>
  </div>
</div>
</div>
    <!--End Modal -->
                             <div class="element-box col-lg-12">
                                                 <div class="">
                                        <div class="table-responsive">
                                            <table id ="example" class="table table-darkborder table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Driver Name</th>
                                                        <th>Driver Phone</th>
                                                        <th class="text-center">Email</th>
                                                        <th class="text-center">Assign</th>
                                                        <th class="text-right">Company</th>
                                                      <th class="text-right">branch</th>
                                                      <th>Delete</th>
                                                      <th>Edit</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                        @foreach($drivers as $driver)
                                                    <tr>
                                                    <td alt="safsfd" class="text-center">{{$driver->name}}</td>
 
                                                                                       
                                                        <td alt="safsfd" class="text-center">{{$driver->phone}}</td>
                                                        <td alt="safsfd" class="text-center">{{ str_limit($driver->email, 15)}}</td>
                                                       
                                                        <td class="text-right"> {{$driver->company}}</td>
                                                       <td class="text-right"> {{$driver->branchname}}</td>

                                                        
<td class="text-right"><a class="btn btn-sm btn-danger" href="{{route('driver.deletedriver',$driver->id)}}" onclick="return confirm('Are you sure you want to delete this?')">Delete</a></td>
<td class="text-right"><a class="btn btn-sm btn-danger"href="{{route('driver.edit', $driver->id)}}">Edit</a>
                                                        
                                                            



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

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

@section('script')

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>



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