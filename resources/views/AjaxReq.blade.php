<!DOCTYPE html>
<html>
    <head>
        <title>Ajax Request</title>
        <script src = "https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <meta name="csrf-token" content="{{ csrf_token() }}" />
    </head>

    <body>
        <div class="input_taken">
            <form>
                Name : <input type="text" name="name"> <br>
                Age : <input type="text" name="age"> <br>
                <button class="submit">Submit</button>
            </form>
        </div>

        <div class="show">
            @foreach($persons as $person)
                Name : {{ $person->name }} <br>
                Age : {{ $person->age }} <br>
            @endforeach
        </div>
    </body>

    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(".submit").on('click', function (e) {
            e.preventDefault();
            var name = $("input[name = name]").val();
            var age = $("input[name=age]").val();

            $.ajax({
                type:'POST' ,
                url: '/ajaxtest',
                data: {name:name,age:age},
                success:function (data) {
                    console.log(data);
                }
            });
        } );

    </script>
</html>