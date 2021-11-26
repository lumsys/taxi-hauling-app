<!DOCTYPE html>

<html>

<head>
    <title>Document</title>
</head>

<body>
<form action="/files" method="POST" enctype="multipart/form-data">
    {{csrf_field()}}
    <input type="text" name="title" placeholder="title">
    <input type="text" name="title" placeholder="description">
    <input type="file" name="file">
    <input type="submit" value="submit">

</form>
</body>
</html>
