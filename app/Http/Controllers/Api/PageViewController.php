<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PageView;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PageViewController extends Controller
{
    /**
     * Dipanggil saat halaman baru dibuka — bikin record baru, durasi masih kosong.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'session_id' => ['required', 'string', 'max:100'],
            'path' => ['required', 'string', 'max:255'],
            'referrer' => ['nullable', 'string', 'max:255'],
        ]);

        $pageView = PageView::create($validated);

        return response()->json(['id' => $pageView->id], 201);
    }

    /**
     * Dipanggil saat orang meninggalkan halaman — isi durasi ke record yang sudah ada.
     */
   public function updateDuration(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'duration_seconds' => ['required', 'integer', 'min:0', 'max:86400'],
        ]);

        $pageView = PageView::find($id);

        if (!$pageView) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $pageView->update(['duration_seconds' => $validated['duration_seconds']]);

        return response()->json(['success' => true]);
    }
}
