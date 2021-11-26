 @extends('layouts.dash')

@section('css')
  <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/buttons/1.4.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />


@endsection
@section('content')

<!--<div class="row">
   
                <div class="col-md-4">
                     <form action="{{route('dashboard.searchstaff')}}" method="POST" enctype="multipart/form-data" >
                                    {{ csrf_field() }}
                    <div class="form-group">
                     <span>Search:</span>  <input type="text" name="search" class="form-control" placeholder="name or phone or email or company">
                    </div>
                </div>
                 
                <div class="col-md-4">
                     <div class="form-group" align="center">
                        <button type="submit" name="filter" id="filter" class="btn btn-info">Filter</button>
                    </form>
                       <a href="{{route('dashboard.getallusers')}}" class="btn btn-default">Reset</a>   
                    </div>
                </div>
        
</div> -->
 @if (\Session::has('success'))
        <div class="alert alert-success prompt">
            <div class="text-right text-danger"><i class="fas fa-times fa-2x" id="close"></i></div>
            {!! \Session::get('success') !!}
            
            
        </div>
    @endif

<div class="element-wrapper">
                                    <div class="element-box">
                                        <div class="table-responsive">
                                            <table id ="example"  class="table table-lightborder">
                                                <thead>
                                                    <tr>
                                                        <th>Driver's Name</th>
                                                        <th>Region</th>
                                                        <th class="text-center">Platenumber</th>
                                                        <th class="text-center">Phone</th>
                                                        <th>Edit</th>
                                                         
                                                <!--        <th class="text-right">Status</th> -->
                                                    </tr>
                                                </thead>
                                                <tbody>
                                      @foreach($drivers as $driver)
                                                    <tr>
                                                        <td>
                                                            <div class="user-with-avatar"><span class="d-none d-xl-inline-block"> {{$driver->name}}</span></div>
                                                        </td>
                                                        <td class="">{{$driver->rname}}</td>
                                                        <td class="text-center">{{ $driver->platenumber }}</td>
                                                        <td class="text-center">{{$driver->phone}}</td>
                                                        <td><a href="{{route('platenumber.edit', $driver->id)}}">Edit</a> </td>
                                                        
                                    <!--                    <td class="text-right"><a class="btn btn-sm btn-primary" href="#">Active</a></td> -->
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
                extend: 'copyHtml5',
                exportOptions: {
                    columns:[ 0, 1, 2,3]
                }
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2,3]
                }
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2,3]
                }
            },
            'colvis'
        ]
            } );
        } );
    </script>
@endsection