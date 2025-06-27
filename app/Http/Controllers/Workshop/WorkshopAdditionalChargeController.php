<?php

namespace App\Http\Controllers\Workshop;

use App\Events\workshopAdditionalEvent;
use App\Http\Controllers\Controller;
use App\Mail\AddWorkshopProposalEmail;
use App\Models\User;
use App\Models\Workshop;
use App\Models\WorkshopAdditionalCharge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class WorkshopAdditionalChargeController extends Controller
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
    public function store(Request $request)
    {
        // dd($request);

        $validator = Validator::make($request->all(), [
            'tipoCargo' => ['required'],
            'monto' => ['required'],
            'horas' => ['required'],
            'nota' => ['required'],
            'proposal_id' => ['required'],
        ]);

        if ($validator->fails()) {
            // Si la validación falla, puedes redirigir o devolver una respuesta con los errores
            flash(translate('Error de validacion al crear cargo adicional'))->error();
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $workshopAdditional= new WorkshopAdditionalCharge();
        $workshopAdditional->tipo_cargo = $request->input('tipoCargo');
        $workshopAdditional->monto = $request->input('monto');
        $workshopAdditional->horas = $request->input('horas');
        $workshopAdditional->nota = $request->input('nota');
        $workshopAdditional->proposal_id = $request->input('proposal_id');
      
    
        $workshopAdditional->save();

        $client = User::find($workshopAdditional->WorkshopServiceProposal->user_id);
        $clientName  = $client->name;
        $clientId  = $client->id;
        $taller = Workshop::find($workshopAdditional->WorkshopServiceProposal->workshop_id);
        $tallerName = $taller->name;

        //notificar a los clientes
        self::make_workshopAdditional_notification($workshopAdditional);


        //envio de correo a cliente con su nueva propuesta

        Mail::to($client->email)->send(new AddWorkshopProposalEmail($clientName, $tallerName, $clientId));
       
       
        // Puedes realizar otras acciones después de guardar los datos

        flash(translate('Cargo adicional realizado con éxito!'))->success();
    
        // return response()->json(['success' => true]);
        return redirect()->back();
    }

    static function make_workshopAdditional_notification($workshopAdditional){
       
        event(new workshopAdditionalEvent($workshopAdditional));

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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $additionalCharge = WorkshopAdditionalCharge::find($id);
        if ($additionalCharge) {
            $additionalCharge->delete();
            // Realizar cualquier otra acción necesaria después de eliminar el elemento
        }
        // Redirigir o devolver una respuesta adecuada

        flash(translate('Cargo adicional eliminado con éxito!'))->success();
    
        // return response()->json(['success' => true]);
        return redirect()->back();
    }
}
