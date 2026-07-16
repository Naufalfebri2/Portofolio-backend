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
        // Honeypot check: this field is invisible to real visitors, so it
        // should always be empty. Bots that auto-fill every form field
        // will fill it in, exposing themselves. We pretend the request
        // succeeded (same response shape) instead of returning a 422 —
        // tipping the bot off would just teach it to leave the field
        // blank next time.
        if ($request->filled('website')) {
            return response()->json([
                'message'      => 'Message sent successfully.',
                'whatsapp_url' => '',
            ], 201);
        }

        $contactMessage = Message::create([
            ...$request->validated(),
            'is_read' => false,
        ]);

        Mail::to(config('mail.from.address'))->send(new NewContactMessage($contactMessage));

        $waNumber = config('services.contact.whatsapp_number');
        $waText = urlencode("Hi, I'm {$contactMessage->name}. I just sent you a message through your portfolio:\n\n\"{$contactMessage->message}\"");
        $whatsappUrl = "https://api.whatsapp.com/send/?phone={$waNumber}&text={$waText}";

        return response()->json([
            'message'      => 'Message sent successfully.',
            'whatsapp_url' => $whatsappUrl,
        ], 201);
    }
}
