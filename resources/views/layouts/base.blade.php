<html>
    <head>
        <title>@yield('title')</title>
        <link rel="stylesheet" href="{{ asset('/css/app.css') }}">
        <style>
            body {
                font-size:16pt;
                margin: 5px;
            }
            h1 {
                font-size:50pt;
                text-align: center;
                margin:20px 0px -30px 0px;
                letter-spacing: -4pt;
            }
            ul {
                font-size:12pt;
            }
            hr {
                margin:25px 100px;
                border-top: 1px dashed #ddd;
            }
            .menutitle {
                font-size: 14pt;
                font-weight: bold;
                margin: 0px;
            }
            .content {
                margin:10px;
            }
            .footer {
                text-align: right;
                font-size: 10pt;
                margin: 10px;
                border-bottom: solid 1px #ccc;
                color: #ccc;
            }
        </style>
    </head>

    <body>
        <h1>@yield('title')</h1>

        <hr size="1">

        <div class="content">
            @yield('content')
        </div>
        <div class="footer">
            @yield('footer')
        </div>
    </body>
</html>