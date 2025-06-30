<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;

class HotelController extends BaseController
{

    private $hotelModel;
    private $status;
    private $message;
    private $code;
    private $data;

    public function __construct()
    {
        $this->hotelModel = new Hotel();
        $this->status = 'success';
        $this->message = 'success';
        $this->code = 201;
        $this->data = [];
    }

    /**
     * Genera una respuesta JSON estandarizada.
     * @return \Illuminate\Http\JsonResponse
     * @author Johan Alexander Farfán Sierra <johanfarfan25@gmail.com>
     */
    private function respose()
    {
        return response()->json([
            'status' => $this->status,
            'message' => $this->message,
            'data' => $this->data
        ], $this->code);
    }

    /**
     * Obtiene una lista de hoteles.
     * @author Johan Alexander Farfán Sierra <johanfarfan25@gmail.com>
     */
    public function index()
    {
        $hotels = $this->hotelModel::select('id', 'uuid', 'name', 'address', 'city', 'nit', 'number_of_rooms')->orderBy('id', 'desc')->get();
        $this->data = $hotels;
        return $this->respose();
    }


    /**
     * Valida los datos de entrada para la creación o actualización de un hotel.
     * @param \Illuminate\Http\Request $data
     * @author Johan Alexander Farfán Sierra <johanfarfan25@gmail.com>
     */
    private function validateData($data)
    {
        return $data->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'nit' => 'nullable|string|max:255',
            'number_of_rooms' => 'required|integer|min:1',
        ]);
    }


    /**
     * Crea un nuevo hotel.
     * @param \Illuminate\Http\Request $request
     * @author Johan Alexander Farfán Sierra <johanfarfan25@gmail.com>
     */
    public function store(Request $request)
    {
        try {

            $data = $this->validateData($request);

            if ($this->hotelModel::where('name', $data['name'])->exists()) {
                return $this->jsonResponse('El hotel ya existe', [], 'error', 400);
            }

            $hotel = $this->hotelModel::create($data);
            $this->data = $hotel;
            return $this->respose();
        } catch (\Exception $e) {
            $this->status = 'error';
            $this->message = $e->getMessage();
            $this->code = 400;
            $this->data = [];
            return $this->respose();
        }
    }

    /**
     * Obtiene los detalles de un hotel específico por su UUID.
     * @author Johan Alexander Farfán Sierra <johanfarfan25@gmail.com>
     */
    public function show(string $uuid)
    {
        $hotel = Hotel::with(['rooms' => function ($query) {
            $query->select('id','uuid', 'status', 'hotel_id', 'room_type', 'accommodation', 'quantity')->orderBy('id', 'desc');
        }])
            ->where('uuid', $uuid)
            ->select('id', 'uuid', 'name', 'address', 'city', 'nit', 'number_of_rooms')
            ->first();

        if (!$hotel) {
            $this->status = 'error';
            $this->message = 'Hotel no encontrado';
            $this->code = 404;
            $this->data = [];
            return $this->respose();
        }

        $hotel->number_of_rooms_assigned = $hotel->rooms->sum('quantity') .' De '.$hotel->number_of_rooms;

        $this->data = $hotel;
        $this->code = 200;
        return $this->respose();
    }


    /**
     * Actualiza un hotel existente.
     * @param \Illuminate\Http\Request $request
     * @author Johan Alexander Farfán Sierra <johanfarfan25@gmail.com>
     */
    public function update(Request $request, string $uuid)
    {
        try {

            $hotel = $this->hotelModel::where('uuid', $uuid)->first();

            if (!$hotel) {
                $this->status = 'error';
                $this->message = 'Hotel no encontrado';
                $this->code = 404;
                $this->data = [];
                return $this->respose();
            }

            if ($this->hotelModel::where('name', $request['name'])->where('uuid', '!=', $uuid)->exists()) {
                $this->status = 'error';
                $this->message = 'Ya existe otro hotel con ese nombre';
                $this->code = 400;
                $this->data = [];
                return $this->respose();
            }

            $data = $this->validateData($request);

            $hotel->update($data);
            $this->data = $hotel;
        } catch (\Exception $e) {
            $this->status = 'error';
            $this->message = $e->getMessage();
            $this->code = 400;
            $this->data = [];
        }

        return $this->respose();
    }


    /**
     * Elimina un hotel por su UUID.
     * @author Johan Alexander Farfán Sierra <johanfarfan25@gmail.com>
     */
    public function destroy(string $uuid)
    {
        $hotel = $this->hotelModel::where('uuid', $uuid)->first();
        if ($hotel) {
            $hotel->delete();
        }
        return $this->respose();
    }
}
