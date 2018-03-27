<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Details Post</title>

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
                        {{ $post['title'] }}
                    </div>
                    <div class="panel-body">
                        {{ $post['description'] }}
                    </div>
                </div>

                <div>
                    <div class="panel panel-default">
                        <div class="panel-heading" >Comments</div>

                        <div id="comments">
                            @foreach($comments as $comment)
                                <div class="panel panel-default panel-body">
                                    <b>{{ $comment['name'] }}: </b> {{$comment['comment']}}
                                </div>
                            @endforeach

                            <div class="form-group">
                                <label for="comment">Comment:</label>
                                <textarea placeholder="Write your comments here ...." class="form-control" rows="3" id="comment"></textarea>
                                <button class="btn btn-primary" id="commentSubmit">Submit</button>
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
    
        $("#commentSubmit").on('click', function (e) {
            e.preventDefault();
            var comment = document.getElementById('comment').value ;
            var courseId = "{!! Request::segment(2) !!}" ;
            var pId = "{!! Request::segment(3) !!}" ;
            var cId = courseId.concat("_");
            cId = cId.concat(pId) ;
            var newHtml = '<div class="panel panel-default panel-body"> <b>' + "{!! $uName !!}" + ': </b>'+ comment  +' </div>' ;
            if(comment.length == 0)
            {
                alert("Please Write Something");
                return ;
            }
    
            $.ajax({
                type:'POST' ,
                url: '/postcomment',
                data: {comment:comment,cId:cId},
                success:function (data) {
                    console.log(data);
                    $("#comments").prepend(newHtml);
                    $("#comment").val("");
                }
            });
        } );
    
    </script>
</html>