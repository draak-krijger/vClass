<!DOCTYPE html>
<html>
    <head>
        <title>Assignments</title>
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
                <h1>{{ $title }}</h1>
            </div>
            <div class="row">
                <p>{{ $description }}</p>
            </div>

            <div class="row">
                <h2>Submitted Assignments By</h2> <br>
                @if($isTeacher)
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Registration Number</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($submitted_by as $submit)
                            <tr>
                                <td>  <a href="{{ url('/download/'.Request::segment(2).'/'.$submit['registrationNum'].'.'.$submit['extention']) }}">{{ $submit['registrationNum'] }}</a>  </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Running Course</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($submitted_by as $submit)
                            <tr>
                                <td>  {{ $submit['registrationNum'] }}  </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            @if($isOpen)
                @if($isTeacher)
                    <div class="closeAssignment">
                        <button class="closeButton btn btn-default">Close</button>
                    </div>
                @else
                    <div class="submitForm">
                        <form id="submitAssignment" enctype="multipart/form-data" method="post" >
                            {{--{{ csrf_field() }}--}}
                            <div class="form-group">
                                <label for="fileInput">Upload Youre File:</label>
                                <input type="file" class="form-control" id="fileInput" name="assignment">
                            </div>
                            <button class="btn btn-default">Submit</button>
                        </form>
                    </div>
                @endif
            @endif
        </div>
    </body>

    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $("form#submitAssignment").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            var value = "{!! Request::segment(2) !!}" ;
            formData.append('assignmentId',value);

            $.ajax({
                url: '/submitAssignment',
                type: 'POST',
                data: formData ,
                success: function (data) {
                    console.log(data);
                    $("#fileInput").val("");
                },
                cache: false,
                contentType: false,
                processData: false
            });
        });

        $(".closeButton").on('click',function (e) {
            e.preventDefault();
            var assignmentId = "{!! Request::segment(2) !!}" ;

            $.ajax({
                type:'POST' ,
                url: '/closeAssignment',
                data: {assignmentId:assignmentId},
                success:function (data) {
                    console.log(data);
                    $(".closeAssignment").hide();
                }
            });
        });

    </script>
</html>