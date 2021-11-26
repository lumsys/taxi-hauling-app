<!DOCTYPE html>

<html>

<head>
    <title>Details</title>
</head>

<body>
<h2>{{$data->title}}</h2>
<h2>{{$data->description}}</h2>
<p>
<iframe src="{{url('storage/'.$data->file}}"></iframe>
</P>
</body>
</html>
