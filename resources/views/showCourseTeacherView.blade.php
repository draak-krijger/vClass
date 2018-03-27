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
                    <div class="col-lg-3">
                        <button class="closeButton">Close</button>
                    </div>
                    <div class="col-lg-3">
                        <a href="{{route('enrolledStudent',Request::segment(2))}}">
                            <button class="btn-default">Enrolled Student</button>
                        </a>
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

            <div class="row">
                <a href=" {{ route('generateList',Request::segment(2)) }} ">
                    <button class="btn-default">Generate Attendance Sheet</button>
                </a>
            </div>

            <h2>{{$title}}</h2>
            <div class="row">
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#AddPost">Add Post</a></li>
                    <li><a data-toggle="tab" href="#addAssignment">Add Assignments</a></li>
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

                    <div id="addKey" class="tab-pane fade">
                        <form id="addnewkey" enctype="multipart/form-data" method="post" >
                            {{--{{ csrf_field() }}--}}
                            Date : <input type="date" class="date" id="keyDate"> <br>
                            Weight : <input type="number" min="0" class="wgt" id="keyWeight"> <br>
                            Attendance File : <input type="file" class="keyfile" name="keyList">  <br>
                            <button class="btn btn-default">Add Key</button>
                        </form>
                    </div>
                </div>
            </div>

             <div class="row">
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#home">Posts</a></li>
                    <li><a data-toggle="tab" href="#menu1">Assignments</a></li>
                </ul>

                 <?php $i = 0; ?>

                <div class="tab-content">
                    <div id="home" class="tab-pane fade in active">
                        <h3>Posts</h3>

                        @foreach($posts as $post)
                            <div class="row">
                                <a href="{{ route('getDetails', [Request::segment(2), "p".$i++ ]) }}">{{ $post['title'] }}</a>
                            </div>
                        @endforeach
                    </div>

                    <div id="menu1" class="tab-pane fade">
                        <h3>Assignments</h3>

                        <?php $i = 0; ?>

                        @foreach($assignments as $assignment)
                            <div class="row">
                                <a href="{{ route('getDetails', [Request::segment(2), "a".$i++ ]) }}">{{ $assignment['title'] }}</a>
                                <a href="{{ url('/assignment/'.$assignment['id']) }}"> <button class="btn-default">Show Submitted</button> </a>
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
            var kDate = document.getElementById('keyDate').value ;
            var kweg = document.getElementById('keyWeight').value ;
            var fle = $(".keyfile").val() ;
            formData.append('courseId',value);
            formData.append('date', kDate);
            formData.append('weight', kweg);

            var Fobject = {};
            formData.forEach(function(value, key){
                Fobject[key] = value;
            });

            if(!Fobject.weight)
            {
                alert("Weight not added");
                return ;
            }

            else if(!Fobject.date)
            {
                alert("Date not added");
                return ;
            }

            else if(!fle)
            {
                alert("Key not added");
                return ;
            }

            //alert(formData[weight]);

            $.ajax({
                url: '/addNewKey',
                type: 'POST',
                data: formData ,
                success: function (data) {
                    console.log(data);
                    $(".keyfile").val("");
                    $(".wgt").val("");
                    $(".date").val("");
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

    </script>

</html>