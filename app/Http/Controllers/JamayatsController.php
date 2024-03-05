<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jamayat;
use App\Models\Tabe3;
use App\Models\Apc;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
//use PDF;
use Carbon\Carbon;
use App\Imports\JamayatImport;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Facades\Excel;
  
//use Mail;
class JamayatsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
            $tabe3s = Tabe3::all();
            $apcs = Apc::all();
            $maintenant = Carbon::now()->format('Y-m-d');
            $depuisTroisAns = Carbon::parse(now()->format('Y-m-d'))->subYears(3);
            Jamayat::where([['akherTarikhTajdid','<',$depuisTroisAns],['nachta', 'نشطة']])->update(['nachta'=>'غيرنشطة']);
            Jamayat::where('akherTarikhTajdid','>=',$depuisTroisAns)->update(['nachta'=>'نشطة']);
            $jamayats = Jamayat::all();
            // $difference = $maintenant->diff($date2);
            return view('jamayats.index',['jamayats'=>$jamayats,'tabe3s'=>$tabe3s,'apcs'=>$apcs,'maintenant'=>$maintenant]);
    }
      /* Show the form for creating a new resource.
     */
    public function create()
    {
            $tabe3s = Tabe3::all();
            $apcs = Apc::all();
            return view('jamayats.create',['tabe3s'=>$tabe3s,'apcs'=>$apcs]);
    }
    /**
     * Store a newly created resource in storage.
     */   
    public function store(Request $request)
    {
        // $request->validate([
        //     'tasmia'=>'required',
        //     'rakm-itimad'=> 'required',
        //     'tarikh-tassiss'=> 'required',
        //     'halat-elmilef'=> 'required',
        //     'tabaa'=> 'required',
        //     'kitaa'=> 'required',
        //     'rakm-itimad'=> 'required',
        //     'nom-president'=> 'required',
        //     'prenom-president'=> 'required',
        //     'email'=> 'required',
        //     'nachta'=> 'required',
        //     'adresse'=> 'required',
        //     'phone'=> 'required',
        //     'baladia'=> 'required',
        //     'description'=> 'required',
        //     'tarikh-tajdid1'=> 'required',
        //     'tarikh-tajdid2'=> 'required',
        //     'tarikh-tajdid3'=> 'required',
        //     'tarikh-tajdid4'=> 'required',
        //     'tarikh-tajdid5'=> 'required',
        // ]);
         Jamayat::create([
            'tasmia'=>$request->input('tasmia'),
            'rakm-itimad'=>$request->input('rakm-itimad'),
            'rakm-itimad1'=>$request->input('rakm-itimad1'),
            'rakm-itimad2'=>$request->input('rakm-itimad2'),
            'rakm-itimad3'=>$request->input('rakm-itimad3'),
            'rakm-itimad4'=>$request->input('rakm-itimad4'),
            'rakm-itimad5'=>$request->input('rakm-itimad5'),
            'rakm-itimad6'=>$request->input('rakm-itimad6'),
            'halat-elmilef'=>$request->hala,
            'tabaa'=>$request->tabe3,
            'kitaa'=>$request->input('kitaa'),
            'rakm-itimad'=>$request->input('rakm-itimad'),
            'nom-president1'=>$request->input('nom-president1'),
            'nom-president2'=>$request->input('nom-president2'),
            'nom-president3'=>$request->input('nom-president3'),
            'nom-president4'=>$request->input('nom-president4'),
            'nom-president5'=>$request->input('nom-president5'),
            'nom-president6'=>$request->input('nom-president6'),
            'nom-president7'=>$request->input('nom-president7'),
            'prenom-president1'=>$request->input('prenom-president1'),
            'prenom-president2'=>$request->input('prenom-president2'),
            'prenom-president3'=>$request->input('prenom-president3'),
            'prenom-president4'=>$request->input('prenom-president4'),
            'prenom-president5'=>$request->input('prenom-president5'),
            'prenom-president6'=>$request->input('prenom-president6'),
            'prenom-president7'=>$request->input('prenom-president7'),
            'email'=>$request->input('email'),
            'nachta'=>$request->nachta,
            'adresse'=>$request->input('adresse'),
            'phone'=>$request->input('phone'),
            'baladia'=>$request->apc,
            'description'=>$request->input('description'),
            'tarikh-tassiss'=>$request->input('tarikh-tassiss'),
            'tarikh-tajdid1'=>$request->input('tarikh-tajdid1'),
            'tarikh-tajdid2'=>$request->input('tarikh-tajdid2'),
            'tarikh-tajdid3'=>$request->input('tarikh-tajdid3'),
            'tarikh-tajdid4'=>$request->input('tarikh-tajdid4'),
            'tarikh-tajdid5'=>$request->input('tarikh-tajdid5'),
            'tarikh-tajdid6'=>$request->input('tarikh-tajdid6'),

            'akherTarikhTajdid'=> max($request->input('tarikh-tassiss'),$request->input('tarikh-tajdid1'),$request->input('tarikh-tajdid2'),$request->input('tarikh-tajdid3'),$request->input('tarikh-tajdid4'),$request->input('tarikh-tajdid5'),$request->input('tarikh-tajdid6')),
            

            'slug'=>Str::of('tasmia')->slug('-'),
            'user_id'=>  auth()->user()->id 
        ]);

        return redirect('/jamayats');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $jamaya = Jamayat::where('id',$id)->first();
        return view('jamayats.show-jamaya',['jamaya'=>$jamaya]);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $jamaya)
    {
        $tabe3s = Tabe3::all();
        $apcs = Apc::all();
        $jamaya = Jamayat::where('id',$jamaya)->first();
        return view('jamayats.edit',['tabe3s'=>$tabe3s,'apcs'=>$apcs,'jamaya'=>$jamaya]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $jamaya)
    {    
        //  $request->validate([
        //     'tasmia'=>'required',
        //     'rakm-itimad'=> 'required',
        //     'tarikh-tassiss'=> 'required',
        //     'halat-elmilef'=> 'required',
        //     'tabaa'=> 'required',
        //     'kitaa'=> 'required',
        //     'rakm-itimad'=> 'required',
        //     'nom-president'=> 'required',
        //     'prenom-president'=> 'required',
        //     'email'=> 'required',
        //     'nachta'=> 'required',
        //     'adresse'=> 'required',
        //    'phone'=> 'required',
         //    'baladia'=> 'required',
        //     'description'=> 'required',
        //     'tarikh-tajdid1'=> 'required',
        //     'tarikh-tajdid2'=> 'required',
        //     'tarikh-tajdid3'=> 'required',
        //     'tarikh-tajdid4'=> 'required',
        //     'tarikh-tajdid5'=> 'required',
        // ]);
        
    Jamayat::where('id',$jamaya)->first()->update([
    'tasmia'=>$request->input('tasmia'),
    'rakm-itimad'=>$request->input('rakm-itimad'),
    'rakm-itimad1'=>$request->input('rakm-itimad1'),
    'rakm-itimad2'=>$request->input('rakm-itimad2'),
    'rakm-itimad3'=>$request->input('rakm-itimad3'),
    'rakm-itimad4'=>$request->input('rakm-itimad4'),
    'rakm-itimad5'=>$request->input('rakm-itimad5'),
    'rakm-itimad6'=>$request->input('rakm-itimad6'),
    'halat-elmilef'=>$request->hala,
    'tabaa'=>$request->tabe3,
    'kitaa'=>$request->input('kitaa'),
    'nom-president1'=>$request->input('nom-president1'),
    'nom-president2'=>$request->input('nom-president2'),
    'nom-president3'=>$request->input('nom-president3'),
    'nom-president4'=>$request->input('nom-president4'),
    'nom-president5'=>$request->input('nom-president5'),
    'nom-president6'=>$request->input('nom-president6'),
    'nom-president7'=>$request->input('nom-president7'),
    'prenom-president1'=>$request->input('prenom-president1'),
    'prenom-president2'=>$request->input('prenom-president2'),
    'prenom-president3'=>$request->input('prenom-president3'),
    'prenom-president4'=>$request->input('prenom-president4'),
    'prenom-president5'=>$request->input('prenom-president5'),
    'prenom-president6'=>$request->input('prenom-president6'),
    'prenom-president7'=>$request->input('prenom-president7'),
    'tarikh-tassiss'=>$request->input('tarikh-tassiss'),
    'tarikh-tajdid1'=>$request->input('tarikh-tajdid1'),
    'tarikh-tajdid2'=>$request->input('tarikh-tajdid2'),
    'tarikh-tajdid3'=>$request->input('tarikh-tajdid3'),
    'tarikh-tajdid4'=>$request->input('tarikh-tajdid4'),
    'tarikh-tajdid5'=>$request->input('tarikh-tajdid5'),
    'tarikh-tajdid6'=>$request->input('tarikh-tajdid6'),
    'akherTarikhTajdid'=> max($request->input('tarikh-tassiss'),$request->input('tarikh-tajdid1'),$request->input('tarikh-tajdid2'),$request->input('tarikh-tajdid3'),$request->input('tarikh-tajdid4'),$request->input('tarikh-tajdid5'),$request->input('tarikh-tajdid6')),

    'email'=>$request->input('email'),
    'nachta'=>$request->nachta,
    'adresse'=>$request->input('adresse'),
    'phone'=>$request->input('phone'),
    'baladia'=>$request->apc,
    'description'=>$request->input('description'),
    'user_id'=>  auth()->user()->id 
         ]);
        return redirect('/jamayats');
     }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $jamaya)
    {
        Jamayat::where('id', $jamaya)->delete();
        return redirect()->route('jamayats.index')->with('message','تم الحذف');
    }
    public function jamayyats_pdf( )    
    {
        $jamayats = Jamayat::all();
        return view('jamayats.jamayyatspdf',['jamayats'=>$jamayats]);
    }
    public function jamayyats_pdf_filtree(Request $request)    
    {
        $tabe3s = Tabe3::all();  
        $apcs = Apc::all();
        if ($request->apcs != 'allapcs') {
           $jamayats = Jamayat::where( 'baladia' , $request->apcs)->get();
        }
        if ($request->tabe3 != 'alltabe3') {
            $jamayats = Jamayat::where('tabaa', 'LIKE' , '%'.$request->tabe3.'%')->get();
        }
        if ($request->wad3ia != 'all0and1') {
            $jamayats = Jamayat::where('nachta',$request->wad3ia)->get();
        }
        if ($request->apcs!= 'allapcs' && $request->tabe3 != 'alltabe3'  ) {
            $jamayats = Jamayat::where([['baladia' , 'LIKE' , '%'.$request->apcs .'%'],['tabaa', 'LIKE' , '%'.$request->tabe3 .'%']])->get();
        }
        if ($request->apcs!= 'allapcs' && $request->wad3ia!= 'all0and1'  ) {
            $jamayats = Jamayat::where([['baladia' , 'LIKE' , '%'.$request->apcs .'%'],['nachta',$request->wad3ia]])->get();
        }
        if ($request->tabe3!= 'alltabe3' && $request->wad3ia!= 'all0and1'  ) {
            $jamayats = Jamayat::where([['tabaa', 'LIKE' , '%'.$request->tabe3 .'%'],['nachta',$request->wad3ia]])->get();
        }
        if ($request->tabe3!= 'alltabe3' && $request->apcs!= 'allapcs' && $request->wad3ia!= 'all0and1' ) {
            $jamayats = Jamayat::where([['tabaa', 'LIKE' , '%'.$request->tabe3 .'%'],['baladia' , 'LIKE' , '%'.$request->apcs .'%'],['nachta',$request->wad3ia]])->get();
        }
        if ($request->tabe3 === 'alltabe3' && $request->apcs === 'allapcs' && $request->wad3ia === 'all0and1' ) {
            $jamayats = Jamayat::all();
        }
        return view('jamayats.jamayyatspdf ',['jamayats'=>$jamayats,'tabe3s'=>$tabe3s,'apcs'=>$apcs]);
     }
    public function versjamayatspdffiltree()
     {    $tabe3s = Tabe3::all();
          $apcs = Apc::all();
          $jamayats = Jamayat::all();
          return view('jamayats.jamayatspdffiltree',['jamayats'=>$jamayats,'tabe3s'=>$tabe3s,'apcs'=>$apcs]);
     } 
    public function send_email_pdf()
    {
        $data["email"] = "etp.achihocine@gmail.com";
        $data["title"] = "about mail and pdf";
        $data["body"] = "Hello I am nassima toureche";
        $pdf = PDF::loadView('test', $data);
        Mail::send('test', $data, function($message)use($data, $pdf) {
            $message->to($data["email"])
                    ->subject($data["title"])
                    ->attachData($pdf->output(), "test.pdf");
        });
        dd('Mail sent successfully');
    }
    /**
     * فلترة  .
     */
    public function filtreapcs(Request $request)
    {
        $tabe3s = Tabe3::all();
        $apcs = Apc::all();
        if ($request->apc != 'allapcs') {
            $jamayats = Jamayat::where( 'baladia' , 'LIKE' , '%'.$request->apc .'%')->get();
        }
        if ($request->tabe3 != 'alltabe3') {
            $jamayats = Jamayat::where('tabaa', 'LIKE' , '%'.$request->tabe3.'%')->get();
        }
        if ($request->wad3ia != 'all0and1') {
            $jamayats = Jamayat::where('nachta',$request->wad3ia)->get();
        }
        if ($request->apc != 'allapcs' && $request->tabe3 != 'alltabe3'  ) {
            $jamayats = Jamayat::where([['baladia' , 'LIKE' , '%'.$request->apc.'%'],['tabaa', 'LIKE' , '%'.$request->tabe3.'%']])->get();
        }
        if ($request->apc != 'allapcs' && $request->wad3ia != 'all0and1'  ) {
            $jamayats = Jamayat::where([['baladia' , 'LIKE' , '%'.$request->apc.'%'],['nachta',$request->wad3ia]])->get();
        }
        if ($request->tabe3 != 'alltabe3' && $request->wad3ia != 'all0and1'  ) {
            $jamayats = Jamayat::where([['tabaa', 'LIKE' , '%'.$request->tabe3 .'%'],['nachta',$request->wad3ia]])->get();
        }
        if ($request->tabe3!= 'alltabe3' && $request->apc!= 'allapcs' && $request->wad3ia!= 'all0and1' ) {
            $jamayats = Jamayat::where([['tabaa', 'LIKE' , '%'.$request->tabe3 .'%'],['baladia' , 'LIKE' , '%'.$request->apc.'%'],['nachta',$request->wad3ia]])->get();
        }
        if ($request->tabe3 === 'alltabe3' && $request->apc === 'allapcs' && $request->wad3ia === 'all0and1' ) {
            $jamayats = Jamayat::all();
        }


        return view('jamayats.index ',['jamayats'=>$jamayats,'tabe3s'=>$tabe3s,'apcs'=>$apcs]);
    }

    public function import()
     {
        return view('jamayats.import');

     }
     public function importExcelFile(Request $request)
     {
        Excel::import(new JamayatImport,$request -> file('import-file'));
        return redirect()->back()->with('status','تم الاستيراد بنجاح');
     }
     

    // public function compare(string $id)
    // {
    //     $jamaya = Jamayat::where('id',$id)->first();
    //     $tabe3s = Tabe3::all();
    //     $apcs = Apc::all();
    //     dd($jamaya);
    //     $maintenant = Carbon::now()->format('Y-m-d');
        
    //     // $difference = $maintenant->diff($date2);
    //     return view('jamayats.index',['jamayats'=>$jamayats,'tabe3s'=>$tabe3s,'apcs'=>$apcs,'maintenant'=>$maintenant]);
    // }
}
