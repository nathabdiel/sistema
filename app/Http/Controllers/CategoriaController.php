<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Illuminate\Support\Facades\DB;
use App\Categoria;


class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $categorias = Categoria::paginate(5);

        return [
            'pagination' => [
                'total'        => $categorias-> total(),
                'current_page' => $categorias-> currentPage(),
                'per_page'     => $categorias-> perPage(),
                'last_page'    => $categorias-> lastPage(),
                'from'         => $categorias-> firstItem(),
                'to'           => $categorias-> lastItem(),
            ],
            'categorias' =>$categorias
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $categoria = new Categoria();
        $categoria -> nombre = $request -> nombre;
        $categoria -> descripcion = $request -> descripcion;
        $categoria -> condicion = "1";
        $categoria -> save();
    }

    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $categoria = Categoria::findOrFail($request -> id);
        $categoria -> nombre = $request -> nombre;
        $categoria -> descripcion = $request -> descripcion;
        $categoria -> condicion = "1";
        $categoria -> save();
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $categoria = Categoria::findOrFail($request -> id);
        $categoria -> condicion = "0";
        $categoria -> save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $categoria = Categoria::findOrFail($request -> id);
        $categoria -> condicion = "1";
        $categoria -> save();
    }
    
}
