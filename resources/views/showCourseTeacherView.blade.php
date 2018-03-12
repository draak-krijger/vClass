<!DOCTYPE html>
<html>
    <head>
        <title>Teacher View Courses</title>
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
                <div class="row">
                    <div class="alert alert-success col-lg-12">
                        This Course is now <strong>Open</strong> !
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <button class="closeButton">Close</button>
                    </div>
                    <div class="col-lg-6">
                        <form id="addStudent" enctype="multipart/form-data" method="post" >
                            {{--{{ csrf_field() }}--}}
                            <div class="form-group">
                                <label for="fileInput">Student List:</label>
                                <input type="file" class="form-control" id="fileInput" name="studentList">
                            </div>
                            <button class="btn btn-default">Submit</button>
                        </form>
                    </div>
                </div>
            @else
                <div class="alert alert-danger">
                    This Course is <strong>Closed</strong> :(
                </div>

            @endif

            <h2>{{$title}}</h2>
            <div class="row">
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#AddPost">Add Post</a></li>
                    <li><a data-toggle="tab" href="#addAssignment">Add Assignments</a></li>
                    <li><a data-toggle="tab" href="#addResult">Add Result</a></li>
                    <li><a data-toggle="tab" href="#addKey">Add AttendanceKey</a></li>
                </ul>

                <div class="tab-content">
                    <div id="AddPost" class="tab-pane fade in active">
                        <form>
                            Title : <input type="text" class="postTitle" name="postTitle"> <br>
                            Description : <textarea class="postDescription" name="postDescription"></textarea> <br>
                            <button class="postSubmit btn btn-default">Submit</button>
                        </form>
                    </div>

                    <div id="addAssignment" class="tab-pane fade">
                        <form>
                            Title : <input type="text" class="assignmentTitle" name="assignmentTitle"> <br>
                            Description : <textarea class="assignmentDescription" name="assignmentDescription"></textarea> <br>
                            <button class="assignmentSubmit btn btn-default">Submit</button>
                        </form>
                    </div>

                    <div id="addResult" class="tab-pane fade">
                        <form>
                            Title : <input type="text" class="resultTitle" name="resultTitle"> <br>
                            DownLoad Link:  <textarea class="resultLink" name="resultLink"></textarea> <br>
                            <button class="resultSubmit btn btn-default">Submit</button>
                        </form>
                    </div>

                    <div id="addKey" class="tab-pane fade">
                        <form id="addnewkey" enctype="multipart/form-data" method="post" >
                            {{--{{ csrf_field() }}--}}
                            <div class="form-group">
                                <input type="file" class="form-control keyfile" name="keyList">
                            </div>
                            <button class="btn btn-default">Add Key</button>
                        </form>
                    </div>
                </div>
            </div>

             <div class="row">
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#home">Posts</a></li>
                    <li><a data-toggle="tab" href="#menu1">Assignments</a></li>
                    <li><a data-toggle="tab" href="#menu2">Results</a></li>
                </ul>

                 <?php $i = 1; ?>

                <div class="tab-content">
                    <div id="home" class="tab-pane fade in active">
                        <h3>Posts</h3>

                        @foreach($posts as $post)
                            <div class="row">
                                <a data-toggle="modal" data-target="#myModal{{$i}}">{{ $post['title'] }}</a>

                                <!-- Modal -->
                                <div class="modal fade" id="myModal{{$i++}}" role="dialog">
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

        $("form#addnewkey").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            var value = "{!! Request::segment(2) !!}" ;
            formData.append('courseId',value);

            $.ajax({
                url: '/addNewKey',
                type: 'POST',
                data: formData ,
                success: function (data) {
                    console.log(data);
                    $(".keyfile").val("");
                },
                cache: false,
                contentType: false,
                processData: false
            });
        });

        $("form#addStudent").submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            var value = "{!! Request::segment(2) !!}" ;
            formData.append('courseId',value);

            $.ajax({
                url: '/addNewStudent',
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
            var courseId = "{!! Request::segment(2) !!}" ;

            $.ajax({
                type:'POST' ,
                url: '/closeCourse',
                data: {courseId:courseId},
                success:function (data) {
                    console.log(data);
                }
            });
        });

        $(".postSubmit").on('click',function (e) {
            e.preventDefault();
            var courseId = "{!! Request::segment(2) !!}" ;
            var title = $("[name=postTitle]").val();
            var description = $("[name=postDescription]").val();

            $.ajax({
                type:'POST' ,
                url: '/postInfo',
                data: {courseId:courseId,title:title,description:description},
                success:function (data) {
                    console.log(data);
                    $(".postTitle").val("");
                    $(".postDescription").val("");
                }
            });
        });

        $(".assignmentSubmit").on('click',function (e) {
            e.preventDefault();
            var courseId = "{!! Request::segment(2) !!}" ;
            var title = $("[name=assignmentTitle]").val();
            var description = $("[name=assignmentDescription]").val();

            $.ajax({
                type:'POST' ,
                url: '/postAssignment',
                data: {courseId:courseId,title:title,description:description},
                success:function (data) {
                    console.log(data);
                    $(".assignmentTitle").val("");
                    $(".assignmentDescription").val("");
                }
            });
        });

        $(".resultSubmit").on('click',function (e) {
            e.preventDefault();
            var courseId = "{!! Request::segment(2) !!}" ;
            var title = $("[name=resultTitle]").val();
            var link = $("[name=resultLink]").val();

            $.ajax({
                type:'POST' ,
                url: '/postResult',
                data: {courseId:courseId,title:title,link:link},
                success:function (data) {
                    console.log(data);
                    $(".resultTitle").val("");
                    $(".resultLink").val("");
                }
            });
        });

    </script>

</html>