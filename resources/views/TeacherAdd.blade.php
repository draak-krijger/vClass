<!DOCTYPE html>
<html>
<head>
<title>Add Teacher</title>
</head>
<body>

<form method="POST" action="{{ route('postTeacherAdd') }}">
	{{ csrf_field() }}
	Name: <input type="text" name="name"><br>
	Mail: <input type="text" name="email"><br>
	Password: <input type="password" name="password"><br>
	Confirm Password: <input type="password" name="password_confirmation"><br>
	<button type="submit">Submit</button>
</form>

</body>
</html>