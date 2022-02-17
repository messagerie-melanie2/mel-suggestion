<?php

namespace App\Http\Controllers;

use App\Models\sugestion as ModelsSugestion;
use Illuminate\Http\Request;
use App\Models\Suggestion;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Spatie\SimpleExcel\SimpleExcelReader;
use App\Models\User;


use Illuminate\Routing\Controller as BaseController;

class GeneratecsvController extends  BaseController
{
	public function __construct()
	{
		$this->user = new User();
	}

	// Importer les données ...
	// Exporter les données
	public function export()
	{

		// 1. Validation des informations du formulaire
		
		// 2. Le nom du fichier avec l'extension : .xlsx ou .csv
		$file_name = "ListeSuggestion" . "." . ".csv";
		// 3. On récupère données de la table "clients"
		$Sugestions = Suggestion::where('instance', $this->user->instance)->get();


		// 4. $writer : Objet Spatie\SimpleExcel\SimpleExcelWriter
		$writer = SimpleExcelWriter::streamDownload($file_name);
		// 5. On insère toutes les lignes au fichier Excel $file_name
		$writer->addRows($Sugestions->toArray());
		// 6. Lancer le téléchargement du fichier
		$writer->toBrowser();
	}
}
