<?php

namespace App\Http\Controllers;

use App\Models\BusinessDateNonWorking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BusinessDateNonWorkingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, BusinessDateNonWorking $businessDateNonWorking)
    {
       

        $validator = Validator::make($request->all(), [
            'fecha_no_laborable' => ['required'],
            'nota' => ['required'],
        ]);
    
        if ($validator->fails()) {
            // Si la validación falla, puedes redirigir o devolver una respuesta con los errores
            flash(translate('Error de validacion al crear Fechas no laborable'))->error();
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $businessDateNonWorking->shop_id = Auth::user()->shop->id;
        $businessDateNonWorking->fecha_no_laborable = $request->input('fecha_no_laborable');
        $businessDateNonWorking->nota = $request->input('nota');
    
        if ($businessDateNonWorking->save()) {
            flash(translate('Se creo correctamente Fechas no laborable'))->success();
            return back();
        }

        flash(translate('Error de validacion'))->error();
        return back();

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    { 
        $validator = Validator::make($request->all(), [
            'fecha_no_laborable' => ['required'],
            'nota' => ['required'], 
        ]);

        if ($validator->fails()) {
            flash(translate('Error de validacion al editar Fechas no laborable'))->error();
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $businessDateNonWorking = BusinessDateNonWorking::findOrFail($id);

        $businessDateNonWorking->fecha_no_laborable = $request->input('fecha_no_laborable');
        $businessDateNonWorking->nota = $request->input('nota');
        

        if ($businessDateNonWorking->save()) {
            flash(translate('Fechas no laborable editado correctamente'))->success();
            return redirect()->back();
        }

        flash(translate('Error al editar Fechas no laborable'))->error();
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $businessDateNonWorking = BusinessDateNonWorking::findOrFail($id);
        
        if ($businessDateNonWorking->forceDelete()) {
            flash(translate('Fechas no laborable eliminado correctamente'))->success();
            return redirect()->back();
        } else {
            flash(translate('Error al eliminar la fecha no laborable'))->error();
            return redirect()->back();
        }
    }

}
