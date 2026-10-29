<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Http\Requests\StoreParticipantRequest;
use Illuminate\Http\Request;

class ParticipantController extends Controller
{
    public function create()
    {
        return view('welcome');
    }

    public function store(StoreParticipantRequest $request)
    {
        Participant::create($request->validated());

        return redirect()->back()->with('success', 'Tu participación ha sido registrada correctamente.');
    }

    public function index()
    {
        $participants = Participant::latest()->get();
        return view('dashboard', compact('participants'));
    }

    public function tombola()
    {
        return view('tombola');
    }

    public function getWinner()
    {
        // Obtiene los IDs que ya salieron para no repetirlos
        $excluidos = session()->get('ganadores_excluidos', []);

        // Busca al azar solo entre los participantes que no han salido
        $winner = Participant::whereNotIn('id', $excluidos)->inRandomOrder()->first();

        if (!$winner) {
            return response()->json([
                'error' => 'Ya no quedan más participantes disponibles en la tómbola.'
            ]);
        }

        // Registra al participante actual en la lista de excluidos de la tómbola
        $excluidos[] = $winner->id;
        session()->put('ganadores_excluidos', $excluidos);

        return response()->json($winner);
    }

    public function resetTombola()
    {
        // Limpia la memoria de excluidos para volver a incluirlos a todos si se requiere
        session()->forget('ganadores_excluidos');

        return response()->json(['success' => true]);
    }
}