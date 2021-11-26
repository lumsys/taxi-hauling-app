<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    

    <!-- scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAsjKM16fbsmVRNU4jlrhn3yinTyu3z5JU&sensor=false&libraries=places"></script>
    <title>Create Uncaptured Trip</title>
  </head>
  <body>
@include("uncaps.navbar")


    
    <div class="container-fluid">
      <div class="row">
        <section class="col-md-3">

        </section>
        <div class="col-md-6 card my-5 py-4">
    <form action="/uncaps/store" method="post" id="myform">
    {{ csrf_field() }}
    @if (\Session::has('success'))
        <div class="alert alert-success prompt">
            <div class="text-right text-danger"><i class="fas fa-times fa-2x" id="close"></i></div>
            {!! \Session::get('success') !!}
            
            
        </div>
    @endif
   <div class="form-group">
    <label>Staff Name</label>
    <select name="staff" id="" class="form-control">
        <option value="">Select staff</option>
        @foreach($staff as $person)
            <option value="{{$person->id}}">{{$person->name}}</option>
        @endforeach
    </select>
  </div>
  <div class="text-danger">{{$errors->first('staff')}}</div>
    <br>
  <div class="form-group">
  <label>From</label>
   <input id="origin" name="origin" type="text" class="form-control" required placeholder="Picup Address">
   <!--<input id="origin" type="hiden" name="origin" required/>!-->
   </div>
<br>
  <div class="form-group">
  <label>To</label>
    <input id="destination" name="destination" type="text" class="form-control" required="" placeholder="End trip Address">
  <!--  <input id="destination" type="hiden" name="destination" required/>!-->
 </div>
   <br>
   <div class="form-group">
  <label>Distance</label>
    <input id="distance" name="distance" type="text" class="form-control" required="" placeholder="Distance in meters">
  <!--  <input id="destination" type="hiden" name="destination" required/>!-->
 </div>
   <br>
  <div class="form-group"> 
  <label>Start Time</label>
    <input name="startTime" type="time" class="form-control" placeholder="Start trip time" id="starttime">
  </div>
    <br>
  <div class="form-group"> 
  <label>End Time</label>
    <input name="endTime" type="time" class="form-control" placeholder="End trip time" id="endtime">
  </div>
  
   <input type="button" id="subbtn" class="btn btn-info" value="Submit Trip">
  <input type="reset" class="btn btn-info" value="Reset">
</form>
  </div>
    </div>
      </div>
   

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->


    <script>
        $(function () {
            
            var from= document.getElementById('origin');
            var to=document.getElementById('destination');
            var options = {
                componentRestrictions: {country: "Ng"}
                };
            var from_auto= new google.maps.places.Autocomplete(from,options);
            var to_auto= new google.maps.places.Autocomplete(to, options);

            var from_comp='';
            var to_comp='';
            var distance;
            var duration;
            google.maps.event.addListener(from_auto, 'place_changed', function () {
                from_comp=from_auto.getPlace();
                runner();
            });

            google.maps.event.addListener(to_auto, 'place_changed', function () {
                to_comp=to_auto.getPlace();
                runner();
            });

            $('#subbtn').click(function () {
              if ((from_comp !=='')&&(to_comp !=='')) 
                  //if(('#distance') <= Number(56789))
                  {
                var params={
                  srclat:from_comp.geometry.location.lat(),
                  srclng:from_comp.geometry.location.lng(),
                  deslat:to_comp.geometry.location.lat(),
                  deslng:to_comp.geometry.location.lng(),
                  dist:distance,
                  duration:duration,
                };

                post(params);
              }else{
                alert('please enter data');
              }
            });



            
            function runner() {
                if ((from_comp !=='')&&(to_comp !=='')) {
                    var origin=from_comp.geometry.location.lat()+","+from_comp.geometry.location.lng();
                    var destination=to_comp.geometry.location.lat()+","+to_comp.geometry.location.lng();
                    initMap(origin,destination);
                }else{
                    
                }
            };
            // initMap();
            function initMap(origin,destination) {
                const service = new google.maps.DistanceMatrixService(); // instantiate Distance Matrix service
                const matrixOptions = {
                origins: [origin], // technician locations
                destinations: [destination], // customer address
                travelMode: 'DRIVING',
                unitSystem: google.maps.UnitSystem.IMPERIAL
                };
                // Call Distance Matrix service
                service.getDistanceMatrix(matrixOptions, callback);

                // Callback function used to process Distance Matrix response
                function callback(response, status) {
                if (status !== "OK") {
                    alert("Error with distance matrix");
                    return;
                }
                distance=response.rows[0].elements[0].distance.value;
                duration=response.rows[0].elements[0].duration.value;
                // var d = new Date(); // for now

                // var hour=('0' + d.getHours()).slice(-2);
                // var min=('0' + d.getMinutes()).slice(-2);
                // var curr_time=hour+":"+min;
                // var e=d;
                // e.setSeconds(e.getSeconds() + duration);
                // var ehour=('0' + e.getHours()).slice(-2);
                // var emin=('0' + e.getMinutes()).slice(-2);
                // var end_time=ehour+":"+emin;
                // $('#starttime').val(curr_time);
                // $('#endtime').val(end_time);

                //var total = 567;
              var b =  $('#distance').val(distance)

                    if (b <= 567){
                      console.log('success')
                    }
                    else{
                      console.log('not');
                    }
              ;


                // console.log(response.rows[0].elements[0]);
            


                
                }
            }
            function post(params) {

            // The rest of this code assumes you are not using a library.
            // It can be made less wordy if you use one.
            const form = document.getElementById('myform');

            for (const key in params) {
              if (params.hasOwnProperty(key)) {
                const hiddenField = document.createElement('input');
                hiddenField.type = 'hidden';
                hiddenField.name = key;
                hiddenField.value = params[key];

                form.appendChild(hiddenField);
              }
            }

            form.submit();
            }

                
        });
    </script>

    

  </body>
</html>