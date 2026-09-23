<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{asset('assets/default/img/favicon.ico')}}"  rel="icon" />
    <title>Laranza - German Tech</title>
    <style>
 *{
    padding:0;
    margin:0;	
        }
    .landingpage {
      height: 100vh;
      background: #0a0a0a url("{{ asset('assets/image/laranza-landing.jpg')}}") 0 0 no-repeat;
        background-size: auto;
      background-size: cover;
    }
.login{
background-color: #ed1618;
color: white;
position: fixed;
bottom: 20px;
right: 20px;
text-decoration: none;
text-transform: uppercase;
padding: 13px 25px;
font-size: 18px;
border-radius: 50px;
border:solid 5px #ff5b5b;
    }
.login:hover {
background:#222;
border:solid 5px #333;	
	}	
    </style>
</head>
<body>
  <a class="login" href="{{route('auth')}}">Sales Officer Login</a>
  <a href="https://play.google.com/store/apps/details?id=com.laranza" target="_blank">
<div class="landingpage"></div>
</a>
    
</body>
</html>
