<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MessageCapsule;
use Illuminate\Support\Facades\Auth;

class MessageCapsuleController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        if (!$userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $unopenedCapsules = MessageCapsule::where('user_id', $userId)
            ->get();

        return response()->json(['messageCapsules' => $unopenedCapsules]);
    }


    public function create()
    {
        return view('message-capsules.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'open_date' => 'required|date',
        ]);

        $capsule = new MessageCapsule([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'message' => $request->message,
            'open_date' => $request->open_date,
        ]);

        $capsule->save();

        return response()->json([
            'message' => 'Message capsule created successfully!',
            'capsule' => $capsule
        ], 201); // HTTP status code 201 means "Created"
    }

    public function show($id)
    {
        $capsule = MessageCapsule::where('id', $id)->first();

        if (!$capsule) {
            return response()->json(['message' => 'Capsule not found'], 404);
        }

        $response = response()->json(['messageCapsule' => $capsule], 200);

        if (!$capsule->is_opened) {
            $capsule->is_opened = true;
            $capsule->save();
        }

        return $response;
    }


    public function update(Request $request, $id)
    {
        $capsule = MessageCapsule::findOrFail($id);

        if ($capsule->open_date <= now()) {
            $validatedData = $request->validate([
                'message' => 'required|string',
            ]);
            $capsule->update([
                'message' => $validatedData['message'],
            ]);
            return response()->json(['message' => 'Message capsule updated successfully.'], 200);
        } else {
            return response()->json(['error' => 'Cannot edit this capsule before the scheduled opening time.'], 403);
        }
    }
}

