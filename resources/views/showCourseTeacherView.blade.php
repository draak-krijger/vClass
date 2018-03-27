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

            .container {
                padding-top: 300px ;
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
                    <div class="row">
                        <div class="alert alert-success col-lg-12">
                            This Course is now <strong>Open</strong> !
                        </div>
                    </div>
                @else
                    <div class="alert alert-danger">
                        This Course is <strong>Closed</strong> :(
                    </div>
                @endif
    
                <div class="row">
                    <div class="col-lg-4">
                        @if($isOpen)
                            <button class="closeButton btn btn-danger">Close</button>
                        @else
                            <button class="closeButton btn btn-info">Closed</button>
                        @endif
                    </div>
                    <div class="col-lg-4">
                        <a href="{{route('enrolledStudent',Request::segment(2))}}">
                            <button class="btn btn-info">Enrolled Student</button>
                        </a>
                    </div>
                    <div class="col-lg-4">
                        <a href=" {{ route('generateList',Request::segment(2)) }} ">
                            <button class="btn btn-info">Generate Attendance Sheet</button>
                        </a>
                    </div>
                </div>
    
                <div class="row" style="padding-top: 30px">
                    <form id="addStudent" enctype="multipart/form-data" method="post" >
                        {{--{{ csrf_field() }}--}}
                        <div class="form-group">
                            <label for="fileInput">Add New Student:</label>
                            <div class="row">
                                <div class="col-lg-6">
                                    <input type="file" class="form-control" id="fileInput" name="studentList">
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary">Submit</button>
                    </form>
                </div>

                <div class="panel panel-info">
                    <div class="panel-heading">
                            {{$title}}
                    </div>
                    <div class="panel-body">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="row">
                                    <ul class="nav nav-tabs">
                                        <li class="active"><a data-toggle="tab" href="#AddPost">Add Post</a></li>
                                        <li><a data-toggle="tab" href="#addAssignment">Add Assignments</a></li>
                                        <li><a data-toggle="tab" href="#addKey">Add AttendanceKey</a></li>
                                    </ul>
                    
                                    <div class="tab-content">
                                        <div id="AddPost" class="tab-pane fade in active">
                                            <form>
                                                <div class="row" style="padding-left:20px">
                                                    Title : <input style="padding-left:30px" type="text" class="postTitle" name="postTitle">
                                                </div>
                                                <div class="row" style="padding-left:20px">
                                                    Description : <textarea class="postDescription" name="postDescription"></textarea>
                                                </div>
                                                <div class="row" style="padding-left:180px">
                                                    <button style="padding-left:20px" class="postSubmit btn btn-default">Submit</button>
                                                </div>
                                            </form>
                                        </div>
                    
                                        <div id="addAssignment" class="tab-pane fade">
                                            <form>
                                                <div class="row" style="padding-left:20px">
                                                    Title : <input style="padding-left:30px" type="text" class="assignmentTitle" name="assignmentTitle">
                                                </div>
                                                <div class="row" style="padding-left:20px">
                                                    Description : <textarea class="assignmentDescription" name="assignmentDescription"></textarea>
                                                </div>
                                                <div class="row" style="padding-left:180px">
                                                    <button class="assignmentSubmit btn btn-default">Submit</button>
                                                </div>
                                            </form>
                                        </div>
                    
                                        <div id="addKey" class="tab-pane fade">
                                            <form id="addnewkey" enctype="multipart/form-data" method="post" >
                                                {{--{{ csrf_field() }}--}}
                                                <div class="row" style="padding-left:20px">
                                                    Date : <input style="padding-left:35px" type="date" class="date" id="keyDate"> 
                                                </div>
                                                <div class="row" style="padding-left:20px">
                                                    Weight : <input type="number" min="0" class="wgt" id="keyWeight">
                                                </div>
                                                <div class="row" style="padding-left:20px">
                                                    Attendance File : <input type="file" class="keyfile" name="keyList">  
                                                </div>
                                                <div class="row" style="padding-left:180px">
                                                    <button class="btn btn-default">Add Key</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="row">
                                    <ul class="nav nav-tabs">
                                        <li class="active"><a data-toggle="tab" href="#home">Posts</a></li>
                                        <li><a data-toggle="tab" href="#menu1">Assignments</a></li>
                                    </ul>
                    
                                        <?php $i = 1; ?>
                    
                                    <div class="tab-content">
                                        <div id="home" class="tab-pane fade in active">
                                            @foreach($posts as $post)
                                                <div class="row" style="padding-left:30px">
                                                    <?php $tp = $tpost - $i;
                                                        $i++;
                                                    ?>
                                                    <a href="{{ route('getDetails', [Request::segment(2), "p".$tp ]) }}">{{ $post['title'] }}</a>
                                                </div>
                                            @endforeach
                                        </div>
                    
                                        <div id="menu1" class="tab-pane fade">
                    
                                            <?php $i = 1; ?>
                    
                                            @foreach($assignments as $assignment)
                                                <div class="row" style="padding-left:30px">
                                                    <?php $tp = $tas - $i;
                                                        $i++;
                                                    ?>
                                                    <a href="{{ route('getDetails', [Request::segment(2), "a".$tp ]) }}">{{ $assignment['title'] }}</a>
                                                    <a href="{{ url('/assignment/'.$assignment['id']) }}"> <button class="btn btn-default">Show Submitted</button> </a>
                                                </div>
                                            @endforeach
                    
                                        </div>
                                    </div>
                                </div>
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