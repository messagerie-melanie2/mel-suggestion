
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Page des sugestion</title>
    </head>
    <body>
        <div id="app">
            
        <example-component><example-component/>

        </div>
        <script src="{{ asset('js/app.js') }}"
        props: {
  title: String,
  likes: Number,
  isPublished: Boolean,
  commentIds: Array,
  author: Object,
  callback: Function,
  contactsPromise: Promise 
  
}
        window.posts = @json($posts);
        ></script>
    </body>
</html>