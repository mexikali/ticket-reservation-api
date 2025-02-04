<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use Illuminate\Http\Request;
use Exception;

class VenueController extends Controller
{
    // Tüm mekanları listele
    public function index()
    {
        return response()->json(Venue::all(), 200);
    }

    // Yeni mekan ekle (Admin Only)
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'address' => 'required|string',
                'capacity' => 'required|integer|min:1'
            ]);

            $venue = Venue::create($validated);
            return response()->json($venue, 201);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Failed to create venue',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    // Mekanı güncelle (Admin Only)
    public function update(Request $request, $id)
    {
        try {
            $venue = Venue::find($id);
            if (!$venue) {
                return response()->json(['error' => 'Venue not found'], 404);
            }

            $validated = $request->validate([
                'name' => 'sometimes|string|max:255',
                'address' => 'sometimes|string',
                'capacity' => 'sometimes|integer|min:1'
            ]);

            $venue->update($validated);
            return response()->json($venue, 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Failed to update venue',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    // Mekanı sil (Admin Only)
    public function destroy($id)
    {
        try {
            $venue = Venue::find($id);
            if (!$venue) {
                return response()->json(['error' => 'Venue not found'], 404);
            }

            $venue->delete();
            return response()->json(['message' => 'Venue deleted successfully'], 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Failed to delete venue',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
