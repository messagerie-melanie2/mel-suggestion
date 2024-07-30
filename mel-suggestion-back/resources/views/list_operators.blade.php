<!DOCTYPE html> 
<html lang="en"> 
<head> 
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <script src="https://cdn.tailwindcss.com"></script> 
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <title>Outil de suggestion</title> 
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
    
    <!-- <div class="grid place-items-center pb-8">
        <div class="text-xl font-bold text-gray-900">
            Pour accéder au module de suggestions vous devez être connecté :
        </div>
    </div> -->
    
    <div class="grid place-items-center h-screen pb-40"> 
        <div class="p-4 max-w-sm bg-white rounded-lg border shadow-md sm:p-6 "> 
            <h5 class="mb-3 text-base font-semibold text-gray-900 lg:text-xl text-center">Connexion</h5> 
            <p class="text-sm font-normal text-gray-500 ">Choisissez l'un des opérateurs ci-dessous pour vous connecter au module de suggestions : </p> 
            <ul class="my-4 space-y-3"> 
                @foreach($operators as $operator) 
                <li> 
                    <a href="{{ url('connexion', ['connector' => $operator]) }}" class="flex items-center p-3 text-base font-bold text-gray-900 bg-gray-50 rounded-lg hover:bg-gray-100 group hover:shadow"> 
                        {!! \App\Models\SvgListOperators::getSVGForOperator($operator) !!} 
                        <span class="flex-1 ml-3 whitespace-nowrap">{{ ucfirst($operator) }}</span> 
                    </a> 
                </li> 
                @endforeach 
            </ul> 
        </div> 
    </div> 
</body> 
</html>


