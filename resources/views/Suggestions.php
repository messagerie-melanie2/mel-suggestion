dfd
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Page des sugestion</title>
    </head>
    <body>
        <div id="app">
            
        <example-component :prop=`@json($user)`>><example-component/>

        </div>
        <?php 
    use App\Models\User; 
    $user = new User();
        ?>
        <script src="{{ asset('js/app.js') }}"
      
    
        ></script>
    </body>
</html>