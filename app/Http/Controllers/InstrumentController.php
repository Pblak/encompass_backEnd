<?php

namespace App\Http\Controllers;

use App\Models\Instrument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class InstrumentController extends Controller
{
    public function getInstruments(Request $request)
    {
        return response()->json(Instrument::with(['teachers', 'students'])->get());
    }

    public function getInstrument(Request $request, $id)
    {
        return response()->json(Instrument::find($id));
    }

    public function createInstrument(Request $request)
    {




        DB::beginTransaction();
        try {
            $instrument = Instrument::create($request->all());
            // put the $request->image into the storage into images/instruments/{instrument_id}/icon.png
            // add the absolute path to the image field in the instrument table like this storage/images/instruments/{instrument_id}/icon.png

            if ($request->hasFile('image')) {
                $imageFile = $request->file('image');
                $path = 'images/instruments/' . $instrument->id . '/icon.' . $imageFile->getClientOriginalExtension();
                Storage::disk('public')->put($path, file_get_contents($imageFile->getRealPath()));
                $instrument->image = 'storage/' . $path;
                $instrument->save();
            }


            DB::commit();
            return response()->json($instrument);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateInstrument(Request $request)
    {
        DB::beginTransaction();
        try {
            $instrument = Instrument::findOrFail($request->id);
            $instrument->update($request->all());
            // put the $request->image into the storage into images/instruments/{instrument_id}/icon.png
            // add the absolute path to the image field in the instrument table like this
            // storage/images/instruments/{instrument_id}/icon.png

            if ($request->hasFile('newImage')) {
                $imageFile = $request->file('newImage');
                $path = 'images/instruments/' . $instrument->id . '/icon.' . $imageFile->getClientOriginalExtension();
                Storage::disk('public')->put($path, file_get_contents($imageFile->getRealPath()));
                $instrument->image = 'storage/' . $path;
                $instrument->save();
            }

            DB::commit();
            return response()->json($instrument);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


}
