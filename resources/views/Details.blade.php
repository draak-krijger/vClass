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
            <h2> {{ $post['title'] }} </h2>
            <div>
                <p>
                    {{ $post['description'] }}
                </p>
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