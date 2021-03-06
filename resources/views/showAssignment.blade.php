<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Assignments</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .top-left {
                position: absolute;
                left: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #FD8D74 ;
                padding: 0 25px;
                font-size: 15px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                /* text-transform: uppercase; */
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="top-left links">
                <a href="{{ route('welcome') }}">Virtual Class</a>
            </div>

            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();">
                                            Sign Out
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                    @else
                        <a href="{{ route('login') }}">Sign In</a>
                    @endauth
                </div>
            @endif
            <div class="container">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        {{ $title }}
                    </div>

                    <div class="panel-body">
                        {{ $description }}
                    </div>
                </div>

                <div class="row">
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
                                <th>Registration Number</th>
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