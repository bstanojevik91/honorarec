<?php

namespace App\Mail;

use App\Models\Company;
use App\Models\JobApplication;
use App\Models\JobListing;
use App\Support\PublicUrl;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewJobApplicationNotification extends Mailable
{
    use Queueable, SerializesModels;

    public ?string $cvUrl;

    public function __construct(
        public Company $company,
        public JobListing $jobListing,
        public JobApplication $application,
    ) {
        $this->cvUrl = filled($application->cv_path)
            ? PublicUrl::absolutePath(route('media.public', ['path' => $application->cv_path], false))
            : null;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Нова апликација за оглас: ' . $this->jobListing->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new-application-notification',
        );
    }
}
