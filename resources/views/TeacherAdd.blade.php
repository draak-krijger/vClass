<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Add Teacher</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
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
				<form class="form-horizontal" method="POST" action="{{ route('postTeacherAdd') }}">
					{{ csrf_field() }}

					<div class="form-group">
						<label class="control-label col-sm-2" for="text">Name:</label>
						<div class="col-sm-7">
							<input type="text" class="form-control" placeholder="Enter Teacher Name" name="name">
						</div>
					</div>
					
					<div class="form-group">
						<label class="control-label col-sm-2" for="email">Email:</label>
						<div class="col-sm-7">
							<input type="email" class="form-control" placeholder="Enter Mail" name="email">
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-sm-2" for="password">Password:</label>
						<div class="col-sm-7">
							<input type="password" class="form-control" name="password">
						</div>
					</div>

					<div class="form-group">
						<label class="control-label col-sm-2" for="password_confirmation">Confirm-Password:</label>
						<div class="col-sm-7">
							<input type="password" class="form-control" name="password_confirmation">
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-8">
							<button class="btn btn-primary">Submit</button>
						</div>
					</div>
				</form>
            </div>
        </div>
    </body>
</html>