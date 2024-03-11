<?php

namespace App\Exports;

use App\Models\Jamayat;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;

class JamayatsExportQuery implements FromQuery
{
   use Exportable; 

   protected $selectedJamayats;

   public function __construct($selectedJamayats)
   {
        $this -> selectedJamayats = $selectedJamayats ;
      //dd($this -> selectedJamayats);
   }

    public function query()
    {        
        return Jamayat::whereIn('id',$this -> selectedJamayats);
    }
}
