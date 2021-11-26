 @extends('layouts.dash')

@section('title')
Regions
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
                                <h5 class="form-header">Add Region</h5> 
                                <form action="{{route('region.store')}}" method="POST" >
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                    <label>Name</label>
                                   <input type="text" name="name" class="form-control"   required="required" ><br>
                                   
                                <!--    <label>Profile Pix</label>
                                    <input type ="file" name="profileUrl" class="form-control"  placeholder="image" ><br> -->
     
                                                <center><button class="btn btn-sm btn-primary" name="submit">Add Region</button></center>
                                    </div>
                                </form>
                            </div>
                             </div>
                             <div class="element-box col-lg-9">
                                                 <div class="">
                                        <div class="table-responsive">
                                            <table class="table table-lightborder">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                       
                                                    </tr>
                                                </thead>
                                                <tbody>

                                        @foreach($regions as $region)
                                                    <tr>
                                                        <td>
                                                            <div class="user-with-avatar"><span class="d-none d-xl-inline-block">{{ $region->name }}</span></div>
                                                        </td>
                                                        
                                                <!--        <td class="text-right"><a class="btn btn-sm btn-danger" href="" onclick="return confirm('Are you sure you want to delete this?')">Delete</a></td> -->
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