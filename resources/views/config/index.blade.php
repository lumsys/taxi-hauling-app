 @extends('layouts.dash')

@section('title')
Payment Settings
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
                                <h5 class="form-header">Add Payment</h5> 
                                <form action="{{route('config.store')}}" method="POST" >
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                    <label>Business Account</label>
                                    <select name="unique_code" class="form-control">
                                        @foreach($businesses as $business)
                                        <option value="{{$business->unique_code}}">{{$business->name}}</option>
                                        @endforeach
                                    </select>
                                    <label>Base Fare</label>
                                   <input type="number" name="basefare" class="form-control"   required="required" ><br>
                                   <label>Per Km</label>
                                    <input type="number"  class="form-control" name="perkm"   required="required" ><br>
                                    <label>Per Min</label>
                                    <input type="number" name="permin" class="form-control" required="required"  ><br>
                                     <label>Wait Per Min</label>
                                    <input type="number" name="waitpermin" class="form-control" required="required"  ><br>
                                <!--    <label>Profile Pix</label>
                                    <input type ="file" name="profileUrl" class="form-control"  placeholder="image" ><br> -->
     
                                                <center><button class="btn btn-sm btn-primary" name="submit">Add Payment</button></center>
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
                                                        <th>Business Account</th>
                                                        <th class="text-center">Base Fare</th>
                                                        <th class="text-center">PerKm</th>
                                                        <th class="text-right">PerMin</th>
                                                         <th class="text-right">Wait PerMin</th>
                                                       
                                                    </tr>
                                                </thead>
                                                <tbody>

                                        @foreach($configs as $config)
                                                    <tr>
                                                        <td>
                                                            <div class="user-with-avatar"><span class="d-none d-xl-inline-block">{{$config->accountname['name']}}</span></div>
                                                        </td>
                                                        <td class="text-center">{{$config->basefare}}</td>
                                                        <td class="text-center">{{$config->perkm}}</td>
                                                         <td class="text-center">{{$config->permin}}</td>
                                                        <td class="text-center">{{$config->waitpermin}}</td>
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