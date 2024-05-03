<?php 

namespace App\Models; 

class SvgListOperators 
{ 
    // Fonction pour récupérer le bon SVG en fonction de l'opérateur 
  public static function getSVGForOperator($operator) { 
    switch($operator) { 
        case 'google': 
            return "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 48 48' width='24px' height='24px'> 
                      <path fill='#FFC107' d='M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z' /> 
                      <path fill='#FF3D00' d='M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z' /> 
                      <path fill='#4CAF50' d='M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z' /> 
                      <path fill='#1976D2' d='M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z' /> 
                    </svg>";

        case 'microsoft': 
            return "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 48 48' width='24px' height='24px'> 
                      <path fill='#ff5722' d='M6 6H22V22H6z' transform='rotate(-180 14 14)' /> 
                      <path fill='#4caf50' d='M26 6H42V22H26z' transform='rotate(-180 34 14)' /> 
                      <path fill='#ffc107' d='M26 26H42V42H26z' transform='rotate(-180 34 34)' /> 
                      <path fill='#03a9f4' d='M6 26H22V42H6z' transform='rotate(-180 14 34)' /> 
                    </svg>"; 

        case 'linkedIn': 
            return "<svg xmlns='http://www.w3.org/2000/svg' width='24px' height='24px' viewBox='0 0 256 256'>
                      <path fill='#0a66c2' d='M218.123 218.127h-37.931v-59.403c0-14.165-.253-32.4-19.728-32.4c-19.756 0-22.779 15.434-22.779 31.369v60.43h-37.93V95.967h36.413v16.694h.51a39.907 39.907 0 0 1 35.928-19.733c38.445 0 45.533 25.288 45.533 58.186zM56.955 79.27c-12.157.002-22.014-9.852-22.016-22.009c-.002-12.157 9.851-22.014 22.008-22.016c12.157-.003 22.014 9.851 22.016 22.008A22.013 22.013 0 0 1 56.955 79.27m18.966 138.858H37.95V95.967h37.97zM237.033.018H18.89C8.58-.098.125 8.161-.001 18.471v219.053c.122 10.315 8.576 18.582 18.89 18.474h218.144c10.336.128 18.823-8.139 18.966-18.474V18.454c-.147-10.33-8.635-18.588-18.966-18.453'/>
                    </svg>";

        case 'apple' : 
            return "<svg xmlns='http://www.w3.org/2000/svg' width='24px' height='24px' viewBox='0 0 256 315'> 
                      <path d='M213.803 167.03c.442 47.58 41.74 63.413 42.197 63.615c-.35 1.116-6.599 22.563-21.757 44.716c-13.104 19.153-26.705 38.235-48.13 38.63c-21.05.388-27.82-12.483-51.888-12.483c-24.061 0-31.582 12.088-51.51 12.871c-20.68.783-36.428-20.71-49.64-39.793c-27-39.033-47.633-110.3-19.928-158.406c13.763-23.89 38.36-39.017 65.056-39.405c20.307-.387 39.475 13.662 51.889 13.662c12.406 0 35.699-16.895 60.186-14.414c10.25.427 39.026 4.14 57.503 31.186c-1.49.923-34.335 20.044-33.978 59.822M174.24 50.199c10.98-13.29 18.369-31.79 16.353-50.199c-15.826.636-34.962 10.546-46.314 23.828c-10.173 11.763-19.082 30.589-16.678 48.633c17.64 1.365 35.66-8.964 46.64-22.262'/> 
                    </svg>"; 

        case 'facebook' : 
            return "<svg xmlns='http://www.w3.org/2000/svg' width='24px' height='24px' viewBox='0 0 128 128'> 
                      <rect width='118.35' height='118.35' x='4.83' y='4.83' fill='#3d5a98' rx='6.53' ry='6.53'/> 
                      <path fill='#fff' d='M86.48 123.17V77.34h15.38l2.3-17.86H86.48v-11.4c0-5.17 1.44-8.7 8.85-8.7h9.46v-16A127 127 0 0 0 91 22.7c-13.62 0-23 8.3-23 23.61v13.17H52.62v17.86H68v45.83z'/> 
                    </svg>"; 
 

        // Ajoutez ici a la suite le code SVG d'autres opérateurs OpenID Connect au besoin. 

        default: 
            return ""; 
    } 
  }  
}