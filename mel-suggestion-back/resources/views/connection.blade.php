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
      <h5 class="mb-3 text-base font-semibold text-gray-900 lg:text-xl text-center">
        Se connecter à Suggestion
      </h5>
      <p class="text-sm font-normal text-gray-500 ">Choisissez un moyen pour vous connecter au module de suggestions </p>
      <ul class="my-4 space-y-3">
        <li>
          <a href="{{url('connexion', ['connector' => 'google'])}}" class="flex items-center p-3 text-base font-bold text-gray-900 bg-gray-50 rounded-lg hover:bg-gray-100 group hover:shadow">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="24px" height="24px">
              <path fill="#FFC107" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z" />
              <path fill="#FF3D00" d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z" />
              <path fill="#4CAF50" d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z" />
              <path fill="#1976D2" d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z" />
            </svg>
            <span class="flex-1 ml-3 whitespace-nowrap">avec Google</span>
            <span class="inline-flex items-center justify-center px-2 py-0.5 ml-3 text-xs font-medium text-gray-500 bg-gray-200 rounded ">Populaire</span>
          </a>
        </li>
        <li>
          <a href="{{url('connexion', ['connector' => 'microsoft'])}}" class="flex items-center p-3 text-base font-bold text-gray-900 bg-gray-50 rounded-lg hover:bg-gray-100 group hover:shadow ">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="24px" height="24px">
              <path fill="#ff5722" d="M6 6H22V22H6z" transform="rotate(-180 14 14)" />
              <path fill="#4caf50" d="M26 6H42V22H26z" transform="rotate(-180 34 14)" />
              <path fill="#ffc107" d="M26 26H42V42H26z" transform="rotate(-180 34 34)" />
              <path fill="#03a9f4" d="M6 26H22V42H6z" transform="rotate(-180 14 34)" />
            </svg>
            <span class="flex-1 ml-3 whitespace-nowrap">avec Microsoft</span>
          </a>
        </li>
      </ul>
    </div>
  </div>  
</body>

</html>