<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Contacto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ContactoController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $searchTerm = htmlspecialchars($request->input('search', ''), ENT_QUOTES, 'UTF-8');
        $currentPage = (int) $request->input('page', 1);
        $itemsPerPage = (int) $request->input('itemsPerPage', 10);

        $query = Contacto::with(['emails', 'telefonos', 'direcciones'])
            ->where('user_id', $user->id);

        if ($searchTerm) {
            $query->where(function($q) use ($searchTerm) {
                $q->where('nombre', 'like', "%{$searchTerm}%")
                  ->orWhere('apellido', 'like', "%{$searchTerm}%")
                  ->orWhereHas('emails', function ($q) use ($searchTerm) {
                      $q->where('direccion', 'like', "%{$searchTerm}%");
                  })
                  ->orWhereHas('telefonos', function ($q) use ($searchTerm) {
                      $q->where('numero', 'like', "%{$searchTerm}%");
                  })
                  ->orWhereHas('direcciones', function ($q) use ($searchTerm) {
                      $q->where('calle', 'like', "%{$searchTerm}%")
                        ->orWhere('ciudad', 'like', "%{$searchTerm}%")
                        ->orWhere('estado', 'like', "%{$searchTerm}%")
                        ->orWhere('codigo_postal', 'like', "%{$searchTerm}%");
                  });
            });
        }

        $totalItems = $query->count();

        $contactos = $query->skip(($currentPage - 1) * $itemsPerPage)
            ->take($itemsPerPage)
            ->get();

        return response()->json([
            'data' => $contactos,
            'total' => $totalItems
        ]);
    }

    public function show($id)
    {
        $contacto = Contacto::with(['emails', 'telefonos', 'direcciones'])->find($id);

        if ($contacto) {
            return response()->json($contacto);
        } else {
            return response()->json(['error' => 'Contacto no encontrado'], 404);
        }
    }

    public function create(Request $request)
    {
        $validate = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'empresa' => 'nullable|string|max:255',
            'notas' => 'nullable|string',
            'pagina_web' => 'nullable|url',
            'cumpleanos' => 'nullable|date',
            'telefonos.*.numero' => 'required|string|max:11',
            'telefonos.*.tipo' => 'required|string|max:50',
            'emails.*.direccion' => 'required|string|max:120',
            'direcciones.*.calle' => 'required|string|max:150',
            'direcciones.*.ciudad' => 'required|string|max:150',
            'direcciones.*.estado' => 'required|string|max:150',
            'direcciones.*.codigo_postal' => 'required|string|max:15',
        ]);

        try {
            DB::beginTransaction();

            $contacto = new Contacto();
            $contacto->user_id = Auth::id();
            $contacto->nombre = htmlspecialchars($validate['nombre'], ENT_QUOTES, 'UTF-8');
            $contacto->apellido = htmlspecialchars($validate['apellido'], ENT_QUOTES, 'UTF-8');
            $contacto->empresa = htmlspecialchars($validate['empresa'] ?? '', ENT_QUOTES, 'UTF-8');
            $contacto->notas = htmlspecialchars($validate['notas'] ?? '', ENT_QUOTES, 'UTF-8');
            $contacto->pagina_web = $validate['pagina_web'];
            $contacto->cumpleanos = $validate['cumpleanos'];
            $contacto->save();

            if (isset($request['telefonos'])) {
                foreach ($request['telefonos'] as $telefono) {
                    $contacto->telefonos()->create([
                        'numero' => htmlspecialchars($telefono['numero'], ENT_QUOTES, 'UTF-8'),
                        'tipo' => htmlspecialchars($telefono['tipo'], ENT_QUOTES, 'UTF-8'),
                    ]);
                }
            }

            if (isset($request['emails'])) {
                foreach ($request['emails'] as $email) {
                    $contacto->emails()->create([
                        'direccion' => htmlspecialchars($email['direccion'], ENT_QUOTES, 'UTF-8'),
                    ]);
                }
            }

            if (isset($request['direcciones'])) {
                foreach ($request['direcciones'] as $direccion) {
                    $contacto->direcciones()->create([
                        'calle' => htmlspecialchars($direccion['calle'], ENT_QUOTES, 'UTF-8'),
                        'ciudad' => htmlspecialchars($direccion['ciudad'], ENT_QUOTES, 'UTF-8'),
                        'estado' => htmlspecialchars($direccion['estado'], ENT_QUOTES, 'UTF-8'),
                        'codigo_postal' => htmlspecialchars($direccion['codigo_postal'], ENT_QUOTES, 'UTF-8'),
                    ]);
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Hubo un problema al crear el contacto: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8')], 400);
        }
    }

    public function update(Request $request, $id)
    {
        $validate = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'empresa' => 'nullable|string|max:255',
            'notas' => 'nullable|string',
            'pagina_web' => 'nullable|url',
            'cumpleanos' => 'nullable|date',
            'telefonos.*.id' => 'nullable|integer|exists:telefonos,id',
            'telefonos.*.numero' => 'required|string|max:11',
            'telefonos.*.tipo' => 'required|string|max:50',
            'emails.*.id' => 'nullable|integer|exists:emails,id',
            'emails.*.direccion' => 'required|string|max:120',
            'direcciones.*.id' => 'nullable|integer|exists:direcciones,id',
            'direcciones.*.calle' => 'required|string|max:150',
            'direcciones.*.ciudad' => 'required|string|max:150',
            'direcciones.*.estado' => 'required|string|max:150',
            'direcciones.*.codigo_postal' => 'required|string|max:15',
        ]);

        try {
            DB::beginTransaction();

            $contacto = Contacto::findOrFail($id);
            $contacto->nombre = htmlspecialchars($validate['nombre'], ENT_QUOTES, 'UTF-8');
            $contacto->apellido = htmlspecialchars($validate['apellido'], ENT_QUOTES, 'UTF-8');
            $contacto->empresa = htmlspecialchars($validate['empresa'] ?? '', ENT_QUOTES, 'UTF-8');
            $contacto->notas = htmlspecialchars($validate['notas'] ?? '', ENT_QUOTES, 'UTF-8');
            $contacto->pagina_web = $validate['pagina_web'];
            $contacto->cumpleanos = $validate['cumpleanos'];
            $contacto->save();

            if (isset($request['telefonos'])) {
                $existingTelefonosIds = $contacto->telefonos->pluck('id')->toArray();
                $newTelefonosIds = array_filter(array_column($request['telefonos'], 'id'));
                $idsToDelete = array_diff($existingTelefonosIds, $newTelefonosIds);

                $contacto->telefonos()->whereIn('id', $idsToDelete)->delete();

                foreach ($request['telefonos'] as $telefono) {
                    if (isset($telefono['id'])) {
                        $contacto->telefonos()->where('id', $telefono['id'])->update([
                            'numero' => htmlspecialchars($telefono['numero'], ENT_QUOTES, 'UTF-8'),
                            'tipo' => htmlspecialchars($telefono['tipo'], ENT_QUOTES, 'UTF-8'),
                        ]);
                    } else {
                        $contacto->telefonos()->create([
                            'numero' => htmlspecialchars($telefono['numero'], ENT_QUOTES, 'UTF-8'),
                            'tipo' => htmlspecialchars($telefono['tipo'], ENT_QUOTES, 'UTF-8'),
                        ]);
                    }
                }
            }

            if (isset($request['emails'])) {
                $existingEmailsIds = $contacto->emails->pluck('id')->toArray();
                $newEmailsIds = array_filter(array_column($request['emails'], 'id'));
                $idsToDelete = array_diff($existingEmailsIds, $newEmailsIds);

                $contacto->emails()->whereIn('id', $idsToDelete)->delete();

                foreach ($request['emails'] as $email) {
                    if (isset($email['id'])) {
                        $contacto->emails()->where('id', $email['id'])->update([
                            'direccion' => htmlspecialchars($email['direccion'], ENT_QUOTES, 'UTF-8'),
                        ]);
                    } else {
                        $contacto->emails()->create([
                            'direccion' => htmlspecialchars($email['direccion'], ENT_QUOTES, 'UTF-8'),
                        ]);
                    }
                }
            }

            if (isset($request['direcciones'])) {
                $existingDireccionesIds = $contacto->direcciones->pluck('id')->toArray();
                $newDireccionesIds = array_filter(array_column($request['direcciones'], 'id'));
                $idsToDelete = array_diff($existingDireccionesIds, $newDireccionesIds);

                $contacto->direcciones()->whereIn('id', $idsToDelete)->delete();

                foreach ($request['direcciones'] as $direccion) {
                    if (isset($direccion['id'])) {
                        $contacto->direcciones()->where('id', $direccion['id'])->update([
                            'calle' => htmlspecialchars($direccion['calle'], ENT_QUOTES, 'UTF-8'),
                            'ciudad' => htmlspecialchars($direccion['ciudad'], ENT_QUOTES, 'UTF-8'),
                            'estado' => htmlspecialchars($direccion['estado'], ENT_QUOTES, 'UTF-8'),
                            'codigo_postal' => htmlspecialchars($direccion['codigo_postal'], ENT_QUOTES, 'UTF-8'),
                        ]);
                    } else {
                        $contacto->direcciones()->create([
                            'calle' => htmlspecialchars($direccion['calle'], ENT_QUOTES, 'UTF-8'),
                            'ciudad' => htmlspecialchars($direccion['ciudad'], ENT_QUOTES, 'UTF-8'),
                            'estado' => htmlspecialchars($direccion['estado'], ENT_QUOTES, 'UTF-8'),
                            'codigo_postal' => htmlspecialchars($direccion['codigo_postal'], ENT_QUOTES, 'UTF-8'),
                        ]);
                    }
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Hubo un problema al actualizar el contacto: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8')], 400);
        }
    }

    public function destroy($id)
    {
        try {
            $contacto = Contacto::findOrFail($id);
            $contacto->delete();

            return response()->json(['success' => 'Contacto eliminado correctamente.']);
        } catch (Exception $e) {
            return response()->json(['error' => 'Hubo un problema al eliminar el contacto: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8')], 400);
        }
    }
}
