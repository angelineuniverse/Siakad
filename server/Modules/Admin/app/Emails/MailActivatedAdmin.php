<?php

namespace Modules\Admin\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Modules\Admin\Models\TAdminTab;

class MailActivatedAdmin extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        protected TAdminTab $tAdminTab
    )
    {
        //
    }

    public function envelope()
    {
        return new Envelope(
            from: new Address(env('MAIL_FROM_ADDRESS', 'communityangeline@gmail.com'), env('MAIL_FROM_NAME', 'Angeline SIAKAD')),
            subject: 'Aktivasi Akun Baru',
        );
    }

    public function content(){
        return new Content(
            view: 'admin::mail.activated',
            with: [
                'name' => $this->tAdminTab->name,
                'email' => $this->tAdminTab->email,
                'urls' => env('APP_ADMIN_URL') . '?token='. $this->tAdminTab->createToken('4ngel1n3',['admin'])->plainTextToken,
            ]
        );
    }
}
