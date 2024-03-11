<?php
namespace App\Exports;

use App\Models\Jamayat;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class JamayatsExportView implements FromView
{
   
    public function view(): View
    {
        return view('jamayats.jamayatsexcel', [
            'jamayats' => Jamayat::all()
        ]);
    }
}





