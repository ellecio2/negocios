<?php



namespace App\Http\Controllers;



use App\Models\BusinessWorkingHours;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Validator;



class BusinessWorkingHoursController extends Controller

{

    public function index()

    {



    }



    public function create()

    {



    }



    public function store(Request $request, BusinessWorkingHours $businessWorkingHours)

    {



        $validator = Validator::make($request->all(), [

            'dia_semana' => ['required'],

            'hora_inicio' => ['required'],

            'hora_fin' => ['required'],

            'laborable' => ['required'],

        ]);



        if ($validator->fails()) {

            // Si la validación falla, puedes redirigir o devolver una respuesta con los errores

            flash(translate('Error de validacion al crear Horario Laboral'))->error();

            return redirect()->back()->withErrors($validator)->withInput();

        }



        $businessWorkingHours->shop_id = Auth::user()->shop->id;

        $businessWorkingHours->dia_semana = $request->input('dia_semana');

        $businessWorkingHours->hora_inicio = $request->input('hora_inicio');

        $businessWorkingHours->hora_fin = $request->input('hora_fin');

        $businessWorkingHours->laborable = $request->input('laborable');





        if ($businessWorkingHours->save()) {

            flash(translate('Se creo correctamente Horario Laboral'))->success();

            return back();

        }



        flash(translate('Error de validacion al crear'))->error();

        return back();

    }



    public function edit(BusinessWorkingHours $businessWorkingHours)

    {



    }



    public function update(Request $request, $id)

    {





        $validator = Validator::make($request->all(), [

            'hora_inicio' => ['required'],

            'hora_fin' => ['required'],

            'laborable' => ['required'],

        ]);



        if ($validator->fails()) {

            flash(translate('Error de validacion al editar Horario Laboral'))->error();

            return redirect()->back()->withErrors($validator)->withInput();

        }



        $businessWorkingHours = BusinessWorkingHours::findOrFail($id);



        $businessWorkingHours->hora_inicio = $request->input('hora_inicio');

        $businessWorkingHours->hora_fin = $request->input('hora_fin');

        $businessWorkingHours->laborable = $request->input('laborable');



        if ($businessWorkingHours->save()) {

            flash(translate('Horario Laboral editado correctamente'))->success();

            return redirect()->back();

        }



        flash(translate('Error al editar el Horario Laboral'))->error();

        return redirect()->back();

    }







    public function destroy(BusinessWorkingHours $businessWorkingHours)

    {



    }

}

