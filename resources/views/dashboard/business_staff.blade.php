 @extends('layouts.dash')

@section('content')

<div class="element-wrapper">
                                    <div class="element-box col-lg-12">
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
                                                        
                                                        <td class="text-right"><a class="btn btn-sm btn-primary" href="#">Active</a></td>
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