<?php

namespace App\Mail;

use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TransactionStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $transaction;
 
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }
 
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Order #' . $this->transaction->reference_number . ' - ' . ucfirst($this->transaction->status),
        );
    }
 
    public function content(): Content
    {
        return new Content(
            view: 'emails.transaction-updated',
        );
    }
 
    public function attachments(): array
    { 
        $pdf = Pdf::loadView('admin.reports.receipt', ['transaction' => $this->transaction->load(['user', 'items.product'])]);
        
        return [
            Attachment::fromData(fn () => $pdf->output(), "Receipt-{$this->transaction->reference_number}.pdf")
                ->withMime('application/pdf'),
        ];
    }
}
