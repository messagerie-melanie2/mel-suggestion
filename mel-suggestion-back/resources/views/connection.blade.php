<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Connexion à suggestion</title>
</head>

<body>

  <div class="flex items-center justify-center w-full py-3 bg-white border-b border-gray-200">
    <a href="#" onclick="history.back()" class="relative flex items-center pl-5 font-medium text-gray-700 group lg:w-auto lg:items-center lg:justify-center">
      <span class="w-4 h-4 overflow-hidden transform translate-x-0 group-hover:-translate-x-0.5 absolute left-0 group-hover:w-4 ease-out duration-150 transition">
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd" d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
        </svg>
      </span>
      <span class="mx-auto ml-1 text-sm font-bold leading-none select-none">Retour</span>
    </a>
  </div>
  <div class="grid place-items-center h-screen pb-20">
    <div class="p-4 max-w-sm bg-white rounded-lg border shadow-md sm:p-6 ">
      <h5 class="mb-3 text-base font-semibold text-gray-900 lg:text-xl">
        Se connecter à Suggestion
      </h5>
      <p class="text-sm font-normal text-gray-500 ">Choisir un moyen pour se connecter aux modules de suggestion </p>
      <ul class="my-4 space-y-3">
        <li>
          <a href="{{route('connection.google')}}" class="flex items-center p-3 text-base font-bold text-gray-900 bg-gray-50 rounded-lg hover:bg-gray-100 group hover:shadow">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="24px" height="24px">
              <path fill="#FFC107" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z" />
              <path fill="#FF3D00" d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z" />
              <path fill="#4CAF50" d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z" />
              <path fill="#1976D2" d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z" />
            </svg>
            <span class="flex-1 ml-3 whitespace-nowrap">Google</span>
            <span class="inline-flex items-center justify-center px-2 py-0.5 ml-3 text-xs font-medium text-gray-500 bg-gray-200 rounded ">Populaire</span>
          </a>
        </li>
        <li>
          <a href="{{route('connection.microsoft')}}" class="flex items-center p-3 text-base font-bold text-gray-900 bg-gray-50 rounded-lg hover:bg-gray-100 group hover:shadow ">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="24px" height="24px">
              <path fill="#ff5722" d="M6 6H22V22H6z" transform="rotate(-180 14 14)" />
              <path fill="#4caf50" d="M26 6H42V22H26z" transform="rotate(-180 34 14)" />
              <path fill="#ffc107" d="M26 26H42V42H26z" transform="rotate(-180 34 34)" />
              <path fill="#03a9f4" d="M6 26H22V42H6z" transform="rotate(-180 14 34)" />
            </svg>
            <span class="flex-1 ml-3 whitespace-nowrap">Microsoft</span>
          </a>
        </li>
        <li>
          <a href="{{route('connection.facebook')}}" class="flex items-center p-3 text-base font-bold text-gray-900 bg-gray-50 rounded-lg hover:bg-gray-100 group hover:shadow ">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="24px" height="24px">
              <linearGradient id="Ld6sqrtcxMyckEl6xeDdMa" x1="9.993" x2="40.615" y1="9.993" y2="40.615" gradientUnits="userSpaceOnUse">
                <stop offset="0" stop-color="#2aa4f4" />
                <stop offset="1" stop-color="#007ad9" />
              </linearGradient>
              <path fill="url(#Ld6sqrtcxMyckEl6xeDdMa)" d="M24,4C12.954,4,4,12.954,4,24s8.954,20,20,20s20-8.954,20-20S35.046,4,24,4z" />
              <path fill="#fff" d="M26.707,29.301h5.176l0.813-5.258h-5.989v-2.874c0-2.184,0.714-4.121,2.757-4.121h3.283V12.46 c-0.577-0.078-1.797-0.248-4.102-0.248c-4.814,0-7.636,2.542-7.636,8.334v3.498H16.06v5.258h4.948v14.452 C21.988,43.9,22.981,44,24,44c0.921,0,1.82-0.084,2.707-0.204V29.301z" />
            </svg>
            <span class="flex-1 ml-3 whitespace-nowrap">Facebook</span>
          </a>
        </li>
        <li>
          <a href="{{route('connection.apple')}}" class="flex items-center p-3 text-base font-bold text-gray-900 bg-gray-50 rounded-lg hover:bg-gray-100 group hover:shadow ">
            <svg fill="#000000" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 30" width="24px" height="24px">
              <path d="M25.565,9.785c-0.123,0.077-3.051,1.702-3.051,5.305c0.138,4.109,3.695,5.55,3.756,5.55 c-0.061,0.077-0.537,1.963-1.947,3.94C23.204,26.283,21.962,28,20.076,28c-1.794,0-2.438-1.135-4.508-1.135 c-2.223,0-2.852,1.135-4.554,1.135c-1.886,0-3.22-1.809-4.4-3.496c-1.533-2.208-2.836-5.673-2.882-9 c-0.031-1.763,0.307-3.496,1.165-4.968c1.211-2.055,3.373-3.45,5.734-3.496c1.809-0.061,3.419,1.242,4.523,1.242 c1.058,0,3.036-1.242,5.274-1.242C21.394,7.041,23.97,7.332,25.565,9.785z M15.001,6.688c-0.322-1.61,0.567-3.22,1.395-4.247 c1.058-1.242,2.729-2.085,4.17-2.085c0.092,1.61-0.491,3.189-1.533,4.339C18.098,5.937,16.488,6.872,15.001,6.688z" />
            </svg>
            <span class="flex-1 ml-3 whitespace-nowrap">Apple</span>
          </a>
        </li>
      </ul>
      <div>
        <a href="{{ url('why_connect') }}" class="inline-flex items-center text-xs font-normal text-gray-500 hover:underline">
          <svg class="mr-2 w-3 h-3" aria-hidden="true" focusable="false" data-prefix="far" data-icon="question-circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
            <path fill="currentColor" d="M256 8C119.043 8 8 119.083 8 256c0 136.997 111.043 248 248 248s248-111.003 248-248C504 119.083 392.957 8 256 8zm0 448c-110.532 0-200-89.431-200-200 0-110.495 89.472-200 200-200 110.491 0 200 89.471 200 200 0 110.53-89.431 200-200 200zm107.244-255.2c0 67.052-72.421 68.084-72.421 92.863V300c0 6.627-5.373 12-12 12h-45.647c-6.627 0-12-5.373-12-12v-8.659c0-35.745 27.1-50.034 47.579-61.516 17.561-9.845 28.324-16.541 28.324-29.579 0-17.246-21.999-28.693-39.784-28.693-23.189 0-33.894 10.977-48.942 29.969-4.057 5.12-11.46 6.071-16.666 2.124l-27.824-21.098c-5.107-3.872-6.251-11.066-2.644-16.363C184.846 131.491 214.94 112 261.794 112c49.071 0 101.45 38.304 101.45 88.8zM298 368c0 23.159-18.841 42-42 42s-42-18.841-42-42 18.841-42 42-42 42 18.841 42 42z"></path>
          </svg>
          Pourquoi je dois me connecter ?</a>
      </div>
    </div>
</body>

</html>