
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Page des sugestion</title>
    </head>
    <body>
        <div id="app">
            
        <example-component :prop=`@json($posts[0])`>><example-component/>

        </div>
        <script src="{{ asset('js/app.js') }}"
      
        window.posts = @json($posts);
        ></script>
    </body>
</html>