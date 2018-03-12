<!DOCTYPE html>
<html>
<head>
    <title>Teacher HomePage</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Running Course</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($runningCourses as $course)
                        <tr>
                            <td> <a href=" {{ url('/course/'.$course['id']) }} "> {{ $course['name'] }} </a> </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="col-lg-4">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Closed Course</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($completedCourses as $course)
                        <tr>
                            <td> <a href=" {{ url('/course/'.$course['id']) }} "> {{ $course['name'] }} </a> </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>

</html>