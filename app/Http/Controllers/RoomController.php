<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoomController extends Controller
{
    public function getRooms(Request $request): JsonResponse
    {
        return response()->json(Room::all());
    }

    public function getRoom(Request $request): JsonResponse
    {
        return response()->json(Room::find($request->id));
    }

    public function updateRoom(Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $room = Room::find($request->id);
            $room->update($request->all());
            DB::commit();
            return response()->json([
                'message' => 'Room updated successfully',
                'room' => $room,
                "_t" => "success",
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Room update failed',
                'error' => $e->getMessage(),
                "_t" => "error",
            ]);
        }
    }

    public function createRoom(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required',
            'capacity' => 'required|integer',
            'notes' => 'nullable|string|max:300',
        ]);
        DB::beginTransaction();
        try {
            $room = Room::create($request->all());
            DB::commit();
            return response()->json([
                'message' => 'Room created successfully',
                'room' => $room,
                "_t" => "success",
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Room creation failed',
                'error' => $e->getMessage(),
                "_t" => "error",
            ]);
        }
    }
}
