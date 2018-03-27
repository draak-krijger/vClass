<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Home</title>

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
                <div class="row">
                    <div class="col-lg-12">
                        <button type="button" class="btn btn-primary btn-lg addButton">Add Course</button>
                    </div>
                </div>
                <div class="addForm">
                    <form enctype="multipart/form-data" method="POST" action="{{ route('addCourse') }}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="title">Course Title:</label>
                            <input type="text" class="form-control" id="title" name="title">
                        </div>
                        <div class="form-group">
                            <label for="description">Description:</label>
                            <textarea class="form-control" id="description" name="description"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="fileInput">Student List:</label>
                            <input type="file" class="form-control" id="fileInput" name="studentNumber">
                        </div>
                        <button type="submit" class="btn btn-default">Submit</button>
                    </form>
                </div>

                <div class="row">
                    <div class="col-lg-6">
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

                    <div class="col-lg-6">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Completed Course</th>
                            </tr>
                            </thead>
                            <tbody>
                                {{--<tr>--}}
                                    {{--<td> completed </td>--}}
                                {{--</tr>--}}
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
        </div>
    </body>

    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function(){
            $('.addForm').hide();
            
            $('.addButton').on('click',function () {
                $('.addForm').toggle();
            });

            // $(".courseSubmitButton").submit(function(e) {
            //     e.preventDefault();
            //     var formData = new FormData(this);
            //
            //     $.ajax({
            //         url: '/addCourse',
            //         type: 'POST',
            //         data: formData,
            //         success: function (data) {
            //             console.log(data);
            //         }
            //         // cache: false,
            //         // contentType: false,
            //         // processData: false
            //     });
            // });
        });

        // $(".submit").on('click', function (e) {
        //     e.preventDefault();
        //     var name = $("input[name = name]").val();
        //     var age = $("input[name=age]").val();
        //
        //     $.ajax({
        //         type:'POST' ,
        //         url: '/ajaxtest',
        //         data: {name:name,age:age},
        //         success:function (data) {
        //             console.log(data);
        //         }
        //     });
        // } );

    </script>
</html>