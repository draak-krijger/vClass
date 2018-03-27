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
    <h5>Attendance sheet of {{ $courseName }}</h5>

    <div>
        <table class="table" border="1">
            <thead>
            <tr>
                <th>Reg/date</th>

                @for($i=0 ; $i<sizeof($dates) ; $i++)
                    <th>{{ $dates[$i] }}</th>
                @endfor
                <th>
                    Total <br>
                    {{ $tA }}
                </th>
            </tr>
            </thead>
            <tbody>
            {{--@foreach($runningCourses as $course)--}}
            {{--<tr>--}}
            {{--<td> <a href=" {{ url('/course/'.$course['id']) }} "> {{ $course['name'] }} </a> </td>--}}
            {{--</tr>--}}
            {{--@endforeach--}}
            @for($i=0 ; $i<sizeof($studentReg) ; $i++)
                <tr>
                    <td>{{ $studentReg[$i] }}</td>

                    @for($j=0 ; $j<sizeof($studentsAtt[$i]) ; $j++)
                        <td>{{ $studentsAtt[$i][$j] }}</td>
                    @endfor
                    <td>
                        {{ $Acont[$i] }}
                    </td>
                </tr>
            @endfor
            </tbody>
        </table>
    </div>
</div>
</body>
</html>