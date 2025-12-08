<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address; // Importante para definir las direcciones
use Illuminate\Queue\SerializesModels;

class ContactoFormMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Los detalles del formulario (nombre, email, mensaje).
     * Hacemos que sea público para que la vista pueda acceder a él.
     *
     * @var array
     */
    public $details;

    /**
     * Crea una nueva instancia del mensaje.
     *
     * @param array $details
     */
    public function __construct(array $details)
    {
        $this->details = $details;
    }

    /**
     * Construye el "sobre" del email.
     * Aquí definimos el remitente (from), el destinatario (to), el asunto (subject),
     * y a quién responder (replyTo).
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            // DE: El email siempre se envía DESDE tu dirección verificada en Mailjet.
            from: new Address('petcareoficialtm@gmail.com', 'Formulario Web PetCare'),

            // PARA RESPONDER: Cuando le des a "Responder" en tu gestor de correo,
            // automáticamente se dirigirá al email del usuario que llenó el formulario.
            replyTo: [
                new Address($this->details['email'], $this->details['name']),
            ],
            
            // ASUNTO: Lo que verás en tu bandeja de entrada.
            subject: 'Nuevo Mensaje desde el Formulario de Contacto',
        );
    }

    /**
     * Define el contenido del email.
     * Apunta a la plantilla Blade que se usará para el cuerpo del mensaje.
     */
    public function content(): Content
    {
        return new Content(
            // La vista que se usará para el cuerpo del email.
            // Laravel buscará 'resources/views/emails/contacto.blade.php'
            view: 'emails.contacto',
        );
    }

    /**
     * Define los archivos adjuntos. En este caso, ninguno.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}