<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{

    private $roomModel;
    private $status;
    private $message;
    private $code;
    private $data;

    public function __construct()
    {
        $this->roomModel = new Room();
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
     * Obtiene una lista de habitaciones.
     * @author Johan Alexander Farfán Sierra <johanfarfan25@gmail.com>
     */
    public function index()
    {
        $this->data = $this->roomModel::select('id', 'uuid', 'hotel_id', 'room_type', 'accommodation', 'quantity')->get();
        return $this->respose();
    }

    /**
     * Valida los datos de entrada para la creación o actualización de una habitación.
     * @param \Illuminate\Http\Request $data
     * @author Johan Alexander Farfán Sierra <johanfarfan25@gmail.com>
     */
    private function validateData($data)
    {
        return $data->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'room_type' => 'required|string|max:255',
            'accommodation' => 'required|string|min:1',
            'quantity' => 'integer|min:1',
        ]);
    }

    /**
     * Valida la configuración de la habitación.
     * Verifica si el hotel tiene el número máximo de habitaciones y si ya existe una habitación
     * @author Johan Alexander Farfán Sierra <johanfarfan25@gmail.com>
     */
    private function validateConfigRoom($data, $update = false, $roomUpdate = null)
    {

        if (!$update) {

            $quantity = Room::where('hotel_id', $data['hotel_id'])->sum('quantity');
            $quantity += $request['quantity'] ?? 0;
            $hotel = Hotel::find($data['hotel_id']);

            if ($hotel && $quantity >= $hotel->number_of_rooms) {
                return ['status' => 'error', 'message' => 'El hotel ya tiene el número máximo de habitaciones'];
            }
        }

        $room = Room::where('hotel_id', $data['hotel_id'])
            ->where('room_type', $data['room_type'])
            ->where('accommodation', $data['accommodation'])
            ->first();

        if (!$update && isset($room->id) || $update && isset($room->id) && isset($roomUpdate->id) && $roomUpdate->id != $room->id) {
            return ['status' => 'error', 'message' => 'Ya existe una habitación con esa configuración'];
        }

        if ($data['room_type'] == 'standard' && ($data['accommodation'] == "triple" || $data['accommodation'] == "triple")) {
            return ['status' => 'error', 'message' => 'Tipo de habitación estándar solo admite alojamiento individual o doble'];
        }
        if ($data['room_type'] == 'junior' && ($data['accommodation'] == "single" || $data['accommodation'] == "double")) {
            return ['status' => 'error', 'message' => 'Tipo de habitación junior solo admite alojamiento triple o cuádruple'];
        }
        return false;
    }

    /**
     * Valida si el hotel tiene el número máximo de habitaciones.
     * @author Johan Alexander Farfán Sierra <johanfarfan25@gmail.com>
     */
    private function validateNumberOfRooms($request, $uuid)
    {
        $quantity = Room::where('hotel_id', $request['hotel_id'])->where('uuid', '!=', $uuid)->sum('quantity');

        $quantity += $request['quantity'] ?? 0;

        $hotel = Hotel::find($request['hotel_id']);

        if ($quantity > $hotel->number_of_rooms) {
            return ['status' => 'error', 'message' => 'El hotel ya tiene el número máximo de habitaciones'];
        }
        return false;
    }

    /**
     * Crea una nueva habitación.
     * @param \Illuminate\Http\Request $request
     * @author Johan Alexander Farfán Sierra <johanfarfan25@gmail.com>
     */
    public function store(Request $request)
    {
        try {

            $data = $this->validateData($request);
            $validateConfig = $this->validateConfigRoom($data);

            if ($validateConfig) {
                $this->status = 'error';
                $this->message = $validateConfig['message'];
                $this->code = 400;
                $this->data = [];
                return $this->respose();
            }

            $this->data = $this->roomModel::create($data);
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
     * Obtiene los detalles de una habitación específica por su UUID.
     * @author Johan Alexander Farfán Sierra <johanfarfan25@gmail.com>
     */
    public function update(Request $request, string $uuid)
    {
        try {

            $room = $this->roomModel::where('uuid', $uuid)->first();

            if (!$room) {
                $this->status = 'error';
                $this->message = 'Hotel no encontrado';
                $this->code = 404;
                $this->data = [];
                return $this->respose();
            }

            $validateConfig = $this->validateConfigRoom($request, true, $room);

            if ($validateConfig || ($this->validateNumberOfRooms($request, $uuid))) {
                $this->status = 'error';
                $this->message = $validateConfig['message'] ?? 'El hotel ya tiene el número máximo de habitaciones';
                $this->code = 400;
                $this->data = [];
                return $this->respose();
            }

            $data = $this->validateData($request);

            $room->update($data);
            $this->data = $room;
        } catch (\Exception $e) {
            $this->status = 'error';
            $this->message = $e->getMessage();
            $this->code = 400;
            $this->data = [];
        }

        return $this->respose();
    }

    /**
     * Elimina una habitación por su UUID.
     * @author Johan Alexander Farfán Sierra <johanfarfan25@gmail.com>
     */
    public function destroy(string $uuid)
    {
        $room = $this->roomModel::where('uuid', $uuid)->first();
        if ($room) {
            $room->delete();
        }
        return $this->respose();
    }
}
