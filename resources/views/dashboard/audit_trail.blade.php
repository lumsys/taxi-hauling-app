 @extends('layouts.dash')

@section('title')
All Trips
@endsection
 @section('css')
  <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/buttons/1.4.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />


@endsection
@section('content')
 
<div class="row">
    
</div>
 <div class="element-wrapper">



 

 
                                    <div class="element-box">
                                        <div style="overflow-x: scroll; width: 90%" class="table-responsive" >
                                            <table id ="example" class="table table-lightborder" class="datatable mdl-data-table dataTable">
                                                <thead>
                                                    <tr>
                                                        <th>Model</th>
                                                        <th>Action</th>
                                                        <th class="text-center">User</th>
                                                        <th class="text-center">Time</th>
                                                        <th class="text-center">IP Address</th>
                                                        
                                                    </tr>
                                                </thead>
                                                <tbody>
                                    @foreach($audits as $audit)
                                                    <tr>
                                                        <td>
                                                            <div class="user-with-avatar"><span class="d-none d-xl-inline-block smaller lighter">{{$audit->auditable_type}} (id: {{$audit->auditable_id}})</span></div>
                                                        </td>
                                                        <td>
                                                            <div class="user-with-avatar"><span class="d-none d-xl-inline-block smaller lighter">{{$audit->event}}</span></div>
                                                        </td>
                                                       
                                                        <td class="text-center"><span class="smaller lighter">{{$audit->user['name']}}</span></td>
                                                        <td class="text-center"><span class="smaller lighter">{{$audit->created_at}}</span></td>
                                                        <td class="text-center"><span class="smaller lighter"> {{$audit->ip_address}}</span></td>
                                                        
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

 
<!--

<script>
$(document).ready(function() {
    $('#example').DataTable( {
        "processing": true,
        "serverSide": true,
        "ajax": "{{ route('dashboard.index.server') }}"
    } );
} );
</script>
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
                    columns:[ 0, 1, 2,3,4, 5,6,7,8,9,10,11,12]
                }
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2,3,4, 5,6,7,8,9,10,11,12]
                }
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2,3,4, 5,6,7,8,9,10,11,12]
                }
            },
            'colvis'
        ]
            } );
        } );
    </script> -->
@endsection