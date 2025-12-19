<?php

namespace App\Notifications;

use App\Models\InternshipApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationStatusUpdated extends Notification
{
    use Queueable;

    public function __construct(
        public InternshipApplication $application
    ) {}

    /**
     * Channel apa saja yang dipakai (database + email).
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Data yang disimpan di tabel notifications (in-app).
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'application_id' => $this->application->id,
            'status'         => $this->application->status,
            'industry_name'  => $this->application->industry?->name,
            'teacher_note'   => $this->application->teacher_note,
            'message'        => $this->buildMessageText(),
        ];
    }

    /**
     * Konten email (kalau MAIL_MAILER sudah diset).
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Status Pengajuan Prakerin Anda Berubah')
            ->greeting('Halo, ' . $notifiable->name)
            ->line($this->buildMessageText())
            ->action('Lihat Pengajuan', url('/student/applications'))
            ->line('Silakan cek detail pengajuan di sistem Prakerin.');
    }

    protected function buildMessageText(): string
    {
        $status = $this->application->status;
        $industry = $this->application->industry?->name ?? '-';

        return match ($status) {
            'approved_by_teacher' =>
                "Pengajuan Prakerin Anda ke {$industry} telah disetujui oleh guru pembimbing dan direkomendasikan ke admin.",
            'revision' =>
                "Pengajuan Prakerin Anda dikembalikan untuk revisi oleh guru pembimbing.",
            default =>
                "Status pengajuan Prakerin Anda diperbarui menjadi: {$status}.",
        };
    }
}
