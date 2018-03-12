<!DOCTYPE html>
<html>
<head>
    <title>Student View Courses</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
</head>
<body>
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

    <h2>{{$title}}</h2>

    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#home">Posts</a></li>
        <li><a data-toggle="tab" href="#menu1">Assignments</a></li>
        <li><a data-toggle="tab" href="#menu2">Results</a></li>
        <li><a data-toggle="tab" href="#menu3">Submit Attendance</a></li>
    </ul>

    <div class="tab-content">
        <div id="home" class="tab-pane fade in active">
            <h3>Posts</h3>

            {{--<a data-toggle="modal" data-target="#myModal"> <h2>post title </h2> </a>--}}

            {{--<!-- Modal -->--}}
            {{--<div class="modal fade" id="myModal" role="dialog">--}}
            {{--<div class="modal-dialog">--}}

            {{--<!-- Modal content-->--}}
            {{--<div class="modal-content">--}}
            {{--<div class="modal-header">--}}
            {{--<button type="button" class="close" data-dismiss="modal">&times;</button>--}}
            {{--<h4 class="modal-title">post title</h4>--}}
            {{--</div>--}}
            {{--<div class="modal-body">--}}
            {{--<p>post details</p>--}}
            {{--</div>--}}
            {{--<div class="modal-footer">--}}
            {{--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>--}}
            {{--</div>--}}
            {{--</div>--}}

            {{--</div>--}}
            {{--</div>--}}
            <?php $i = 1; ?>
            @foreach($posts as $post)
                <div class="row">
                    <a data-toggle="modal" data-target="#myModal{{$i}}">{{ $post['title'] }}</a>

                    <!-- Modal -->
                    <div class="modal fade" id="myModal{{ $i++ }}" role="dialog">
                        <div class="modal-dialog">

                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">{{ $post['title'] }}</h4>
                                </div>
                                <div class="modal-body">
                                    <p>{{ $post['description'] }}</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            @endforeach

        </div>
        <div id="menu1" class="tab-pane fade">
            <h3>Assignments</h3>

            @foreach($assignments as $assignment)
                <div class="row">
                    <a href="{{ url('/assignment/'.$assignment['id']) }}">{{ $assignment['title'] }}</a>
                </div>
            @endforeach

        </div>
        <div id="menu2" class="tab-pane fade">
            <h3>Results</h3>
            @foreach($results as $result)
                <div class="row">
                    <a data-toggle="modal" data-target="#myModal{{$i}}">{{ $result['title'] }}</a>

                    <!-- Modal -->
                    <div class="modal fade" id="myModal{{$i++}}" role="dialog">
                        <div class="modal-dialog">

                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">{{ $result['title'] }}</h4>
                                </div>
                                <div class="modal-body">
                                    <p> <a href="{{ $result['link'] }}">Click Here</a> to see/download your result. </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>

                        </div>
                    </div>
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