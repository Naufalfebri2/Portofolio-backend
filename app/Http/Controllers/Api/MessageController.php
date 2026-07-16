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
        // Honeypot check. A real visitor never sees or fills this field
        // (it's hidden off-screen in the Next.js form). If it's filled,
        // silently pretend success without creating a record or sending
        // an email, so the bot gets no signal that it was caught.
        if (filled($request->input('website'))) {
            return response()->json([
                'message'      => 'Pesan berhasil dikirim.',
                'whatsapp_url' => '',
            ], 201);
        }

        $contactMessage = Message::create($request->safe()->except('website'));

        Mail::to(config('mail.from.address'))->send(new NewContactMessage($contactMessage));

        $waNumber = config('services.contact.whatsapp_number');
        $waText = urlencode("Halo, saya {$contactMessage->name}. Saya baru saja mengirim pesan lewat portfolio kamu:\n\n\"{$contactMessage->message}\"");
        $whatsappUrl = "https://api.whatsapp.com/send/?phone={$waNumber}&text={$waText}";

        return response()->json([
            'message'      => 'Pesan berhasil dikirim.',
            'whatsapp_url' => $whatsappUrl,
        ], 201);
    }
}