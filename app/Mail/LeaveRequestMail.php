<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

use App\Models\LeaveRequest;
use App\Models\User;
use App\Models\ModuleFormApprover;

class LeaveRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $leaveRequest, $moduleFormApprover;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(LeaveRequest $leaveRequest, ModuleFormApprover $moduleFormApprover)
    {
        $this->leaveRequest       = $leaveRequest;
        $this->moduleFormApprover = $moduleFormApprover;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Leave Request',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'emails.leaveRequest',
            with: [
                'leaveRequest'       => $this->leaveRequest,
                'moduleFormApprover' => $this->moduleFormApprover
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
