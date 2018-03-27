<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{$title}}</title>

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
                @if($isOpen)
                    <div class="alert alert-success">
                        This Course is now <strong>Open</strong> !
                    </div>

                @else
                    <div class="alert alert-danger">
                        This Course is <strong>Closed</strong> :(
                    </div>

                @endif

                <div class="panel panel-info">
                    <div class="panel-heading">
                        {{$title}}                        
                    </div>

                    <div class="panel-body">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#home">Posts</a></li>
                            <li><a data-toggle="tab" href="#menu1">Assignments</a></li>
                            <li><a data-toggle="tab" href="#menu3">Submit Attendance</a></li>
                        </ul>
        
                        <div class="tab-content">
                            <div id="home" class="tab-pane fade in active">
                                <?php $i = 1; ?>
                                @foreach($posts as $post)
                                    <div class="row">
                                        <?php $tp = $tpost - $i;
                                            $i++; 
                                        ?>
                                        <a style="padding-left:20px" href="{{ route('getDetails', [Request::segment(2), "p".$tp ]) }}">{{ $post['title'] }}</a>
                                    </div>
                                @endforeach
        
                            </div>
        
                            <?php $i = 1; ?>
                            <div id="menu1" class="tab-pane fade">
                                <h3>Assignments</h3>
        
                                @foreach($assignments as $assignment)
                                    <div class="row">
                                        <?php $tp = $tpost - $i;
                                            $i++; 
                                        ?>
                                        <a style="padding-left:20px" href="{{ route('getDetails', [Request::segment(2), "a".$tp ]) }}">{{ $assignment['title'] }}</a>
                                        <a href="{{ url('/assignment/'.$assignment['id']) }}"> <button class="btn btn-default">Submit</button> </a>
                                    </div>
                                @endforeach
        
                            </div>
        
                            <div id="menu3" class="tab-pane fade">
                                @if($isOpen)
                                    <form>
                                        Enter Key : <input type="text" name="key" id="key"> <br>
                                        <button class="key_submit">Submit</button>
                                    </form>
                                @else
                                    <h2>This Course is Closed.</h2>
                                @endif
                            </div>
                        </div>
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

    $(".key_submit").on('click', function (e) {
        e.preventDefault();
        var key = $("input[name = key]").val();
        var courseId = "{!! Request::segment(2) !!}" ;

        $.ajax({
            type:'POST' ,
            url: '/submitAttendence',
            data: {key:key,courseId:courseId},
            success:function (data) {
                console.log(data);

                $("#key").val("");
            }
        });
    } );

</script>

</html>