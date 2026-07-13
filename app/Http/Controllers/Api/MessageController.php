<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactMessageRequest;
use App\Mail\NewContactMessage;
use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;

class MessageController extends Controller
{
    public function store(ContactMessageRequest $request): JsonResponse
    {
        $contactMessage = Message::create([
            ...$request->validated(),
            'is_read' => false,
        ]);

        Mail::to(config('mail.from.address'))->queue(new NewContactMessage($contactMessage));

        $waNumber = config('services.contact.whatsapp_number');
        $waText = urlencode("Halo, saya {$contactMessage->name}. Saya baru saja mengirim pesan lewat portfolio kamu:\n\n\"{$contactMessage->message}\"");
        $whatsappUrl = "https://api.whatsapp.com/send/?phone={$waNumber}&text={$waText}";

        return response()->json([
            'message'      => 'Pesan berhasil dikirim.',
            'whatsapp_url' => $whatsappUrl,
        ], 201);
    }
}
