<!DOCTYPE html>
<html>
    <head>
        <title>Enrolled Student List</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <meta name="csrf-token" content="{{ csrf_token() }}" />
    </head>
    <body>
        <div class="container">
            <h5>Enrolled Students</h5>

            <div class="row">
                <div class="col-lg-6">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Student Registration Number</th>
                            <th>Options</th>
                        </tr>
                        </thead>
                        <tbody>
                        {{--@foreach($runningCourses as $course)--}}
                            {{--<tr>--}}
                                {{--<td> <a href=" {{ url('/course/'.$course['id']) }} "> {{ $course['name'] }} </a> </td>--}}
                            {{--</tr>--}}
                        {{--@endforeach--}}
                        @for($i=0 ; $i<sizeof($studentList) ; $i++)
                            <tr>
                                <td>{{ $studentName[$i] }}</td>
                                <td>
                                    <a href="{{ route('deleteStudent',[Request::segment(2),$studentList[$i]]) }}">
                                        <button class="btn-danger">DELETE</button>
                                    </a>
                                </td>
                            </tr>
                        @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>