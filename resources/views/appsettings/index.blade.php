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
                   
                      <div class="element-box col-lg-2">
                                                 
                             </div>
                            <div class="col-sm-7">
                               <div class="element-box">
                                <h5 class="form-header">App Settings</h5> 
                                <form action="{{route('appsetting.update')}}" method="POST" >
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                @foreach($appsettings as $appsetting)
                                    <label>{{$appsetting->key}}</label>
                                   <input type="text" name="{{$appsetting->key}}" class="form-control" value="{{$appsetting->value}}"   ><br>
                                @endforeach             
                                    <center><button class="btn btn-sm btn-primary">Save Settings</button></center>
                                    </div>
                                </form>
                            </div>
                             </div>
                             <div class="element-box col-lg-2">
                                                 
                             </div>
                            </div></div>
@endsection