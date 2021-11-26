<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index()
    {
        $file=Documents::all();
        return view('document.view',compact('file'));
    }

    public function create()
    {
        return view('document.create');
    } 

    public function store(Request $request)
    {
        $data=new Documents;
        if($request->file('uploadfile')){
            $file=$request->file('ulpoadfile');
            $filename=time().'.'.$file->getClientOriginalExtension();
            $request->file->('public/'. $filename);
            $data->file=$filename; 

        }
        $data->title=$request->title;
        $data->description=$request->description;
        $data->save();
        return redirect()->back();
        
    }

    public function show($id)
    {
        $data=Documents::find();
        return view('document.details',compact('data'));
    }

    public function download($file)
    {
        return response()->download('storage/'.$file);
    }
  
}
