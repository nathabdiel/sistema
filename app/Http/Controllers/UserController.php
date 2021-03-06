<?php

namespace App\Http\Controllers;

use App\User;
use App\Persona;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request -> buscar;
        $criterio = $request -> criterio;

        if ($buscar == ''){
            $personas = User::join('personas', 'users.id', '=', 'personas.id')
                ->join('roles', 'users.idrol', '=', 'roles.id')
                ->select('personas.id', 'personas.nombre', 'personas.tipo_documento',
                    'personas.num_documento', 'personas.direccion', 'personas.telefono',
                    'personas.email', 'users.usuario', 'users.password',
                    'users.condicion', 'users.idrol', 'roles.nombre as rol')
                ->orderBy('personas.id', 'desc')-> paginate(5);
        }
        else {
            $personas = User::join('personas', 'users.id', '=', 'personas.id')
                ->join('roles', 'users.idrol', '=', 'roles.id')
                ->select('personas.id', 'personas.nombre', 'personas.tipo_documento',
                    'personas.num_documento', 'personas.direccion', 'personas.telefono',
                    'personas.email', 'users.usuario', 'users.password',
                    'users.condicion', 'users.idrol', 'roles.nombre as rol')
                ->where('personas.' . $criterio, 'like', '%'. $buscar . '%')
                ->orderBy('personas.id', 'desc') -> paginate(5);
        }


        return [
            'pagination' => [
                'total'        => $personas-> total(),
                'current_page' => $personas-> currentPage(),
                'per_page'     => $personas-> perPage(),
                'last_page'    => $personas-> lastPage(),
                'from'         => $personas-> firstItem(),
                'to'           => $personas-> lastItem(),
            ],
            'personas' =>$personas
        ];
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        try{

            DB::beginTransaction();
            $persona = new Persona();
            $persona -> fill($request->all());
            $persona -> save();

            $user = new User();
            $user ->fill($request->all());
            $user ->password = bcrypt($request->password);
            $user ->condicion = '1';
            $user -> id = $persona->id;
            $user ->save();

            DB::commit();


        } catch (Exception $e){

            DB::rollBack();

        }

    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        try{

            DB::beginTransaction();

            $user = User::findOrFail($request->id);

            $persona = Persona::findOrFail($user->id);

            $persona -> fill($request->all());
            $persona -> save();

            $user ->fill($request->all());
            $user ->password = bcrypt($request->password);
            $user ->condicion = '1';
            $user ->save();

            DB::commit();


        } catch (Exception $e){

            DB::rollBack();

        }
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $user = User::findOrFail($request -> id);
        $user -> condicion = "0";
        $user -> save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $user = User::findOrFail($request -> id);
        $user -> condicion = "1";
        $user -> save();
    }
}
