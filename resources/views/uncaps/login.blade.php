<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <style>
        .fullscreen{
            height: 100vh;
        }
    </style>

    <!-- scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAsjKM16fbsmVRNU4jlrhn3yinTyu3z5JU&sensor=false&libraries=places"></script>
    <title>Login</title>
  </head>
  <body>
    <div class="container pt-5">
        <header class="fullscreen mt-5">
            <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-4 card">
                    <div class="card-header">
                        <h4 class="text-center">Uncaptured Trip Login</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{route('uncaps.login')}}" id="login" method="POST">
                            <div class="form-group mb-3">
                                <input type="text" name="useremail" class="form-control" placeholder="Email" value="{{old('useremail')}}">
                            </div>
                            <div class="form-group">
                                <input type="password" name="userpwd" class="form-control" placeholder="Password">
                            </div>
                            <div class="form-group text-center">
                                <button class="btn btn-primary">Login</button>
                            </div>
                            {{ csrf_field() }}
                        </form>
                        <div class="text-center text-danger">
                            @if(null != $errors->first())
                                invalid login credentials
                            @endif
                            @if (\Session::has('msg'))
                                <div class="alert alert-danger prompt">
                                    
                                    {!! \Session::get('msg') !!}
                                    
                                    
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-4"></div>
            </div>
        </header>
            
    </div>
  </body>
</html>