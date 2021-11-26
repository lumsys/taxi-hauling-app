<!DOCTYPE html>
<html>
<head>
	<title>Waoh</title>
</head>
<body>

<table>
	<thead>
		<tr>
			<th>Class</th>
			<th>Function</th>
			<th>File</th>
			<th>Line</th>
		</tr>
	</thead>
	<tbody>
@foreach ($exception->getTrace() as $trace)
		<tr>
			<td>{{ $trace['class'] }}</td>
			<td>{{ $trace['function'] }}</td>
			<td>{{ $trace['file'] }}</td>
			<td>{{ $trace['line'] }}</td>
		</tr>
@endforeach
	</tbody>
</table>
</body>
</html>