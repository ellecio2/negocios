<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Workshop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProfileWorkshopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $user = auth()->user();

        $addresses = $user->addresses;
       
        $workshop = Workshop::where('user_id', $user->id)->first();

        return view('workshop.profile.index', ['user' => $user, 'workshop' => $workshop, 'addresses' => $addresses]);
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
        //
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
            'name' => ['required'],
            'phone' => ['required'],
        ]);
    
        if ($validator->fails()) {
            // Si la validación falla, puedes redirigir o devolver una respuesta con los errores
            flash(translate('Error de validación'))->error();
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::findOrFail($id);

        $user->name = $request->input('name');
        $user->phone = $request->input('phone');
        // Actualiza los demás campos según tus necesidades
        if ($request->filled('password')) {
            // Verifica si se proporcionó una nueva contraseña
            if (Hash::check($request->input('password'), $user->password)) {
                // Verifica si la contraseña actual es correcta
                $user->password = bcrypt($request->input('password'));
            } else {
                return redirect()->back()->with('error', 'La contraseña actual no es correcta');
            }
        }

        $user->save();
        flash(translate('Se ha editado correctamente los campos'))->success();
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
        //
    }
}
