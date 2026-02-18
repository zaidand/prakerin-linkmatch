<?php

namespace App\Notifications;

use App\Models\InternshipApplication;
use Illuminate\Bus\Queueable;
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
            'student_name'   => $this->resolveStudentName(),
            'industry_name'  => $this->application->industry?->name,
            'teacher_note'   => $this->application->teacher_note,
            'message'        => $this->buildMessageText($notifiable),
        ];
    }

    /**
     * Konten email (kalau MAIL_MAILER sudah diset).
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->buildMailSubject($notifiable))
            ->greeting('Halo, ' . $notifiable->name)
            ->line($this->buildMessageText($notifiable))
            ->action('Lihat Detail', $this->buildActionUrl($notifiable))
            ->line('Silakan cek detail pengajuan di sistem Prakerin.');
    }

    protected function buildMailSubject(object $notifiable): string
    {
        return match ($this->detectRecipientRole($notifiable)) {
            'teacher'  => 'Notifikasi Prakerin - Verifikasi Guru',
            'admin'    => 'Notifikasi Prakerin - Tugas Admin',
            'industry' => 'Notifikasi Prakerin - Konfirmasi Industri',
            default    => 'Notifikasi Prakerin',
        };
    }

    protected function buildActionUrl(object $notifiable): string
    {
        // Arahkan sesuai aktor agar klik email masuk ke tempat yang relevan.
        return match ($this->detectRecipientRole($notifiable)) {
            'teacher'  => url('/teacher/applications'),
            'admin'    => url('/admin/applications'),
            'industry' => url('/industry/dashboard'),
            default    => url('/student/applications'),
        };
    }

    protected function buildMessageText(object $notifiable): string
    {
        $status = $this->application->status;
        $industry = $this->application->industry?->name ?? '-';
        $studentName = $this->resolveStudentName();
        $role = $this->detectRecipientRole($notifiable);

        return match ($role) {
            'student' => match ($status) {
                InternshipApplication::STATUS_WAITING_TEACHER =>
                    'Pengajuan prakerin berhasil dikirim. Menunggu verifikasi Guru Pembimbing.',
                InternshipApplication::STATUS_APPROVED_BY_TEACHER =>
                    'Pengajuan prakerin kamu disetujui Guru Pembimbing dan diteruskan ke Admin.',
                InternshipApplication::STATUS_ASSIGNED_BY_ADMIN =>
                    "Penempatan prakerin ditetapkan ke {$industry}. Menunggu konfirmasi industri.",
                InternshipApplication::STATUS_ACCEPTED =>
                    "Penempatan prakerin diterima oleh {$industry}. Silakan mulai sesuai jadwal.",
                default =>
                    "Status pengajuan prakerin kamu diperbarui menjadi: {$status}.",
            },

            'teacher' => match ($status) {
                InternshipApplication::STATUS_WAITING_TEACHER =>
                    "Ada pengajuan prakerin baru dari {$studentName} menunggu verifikasi.",
                InternshipApplication::STATUS_APPROVED_BY_TEACHER =>
                    "Pengajuan {$studentName} sudah direkomendasikan ke Admin.",
                InternshipApplication::STATUS_ASSIGNED_BY_ADMIN =>
                    "Admin menetapkan penempatan {$studentName} ke {$industry}. Menunggu konfirmasi industri.",
                InternshipApplication::STATUS_ACCEPTED =>
                    "{$studentName} diterima oleh industri {$industry}.",
                default =>
                    "Update pengajuan {$studentName}: status menjadi {$status}.",
            },

            'industry' => match ($status) {
                InternshipApplication::STATUS_ASSIGNED_BY_ADMIN =>
                    "Ada penempatan prakerin baru: {$studentName} menunggu konfirmasi industri.",
                InternshipApplication::STATUS_ACCEPTED =>
                    "Konfirmasi penerimaan prakerin tersimpan untuk {$studentName}.",
                default =>
                    "Ada pembaruan terkait penempatan prakerin {$studentName}: {$status}.",
            },

            // admin
            default => match ($status) {
                InternshipApplication::STATUS_WAITING_TEACHER =>
                    "Pengajuan {$studentName} baru masuk dan menunggu verifikasi Guru Pembimbing.",
                InternshipApplication::STATUS_APPROVED_BY_TEACHER =>
                    "Pengajuan {$studentName} direkomendasikan Guru Pembimbing. Perlu penempatan (assign).",
                InternshipApplication::STATUS_ASSIGNED_BY_ADMIN =>
                    "Penempatan {$studentName} ditetapkan ke {$industry}. Menunggu konfirmasi industri.",
                InternshipApplication::STATUS_ACCEPTED =>
                    "Industri {$industry} menerima {$studentName}.",
                default =>
                    "Update pengajuan {$studentName}: status menjadi {$status}.",
            },
        };
    }

    protected function detectRecipientRole(object $notifiable): string
    {
        // Project kamu jelas pakai relasi singular: $user->role->name
        $roleName = $notifiable->role?->name ?? null;
        if (is_string($roleName) && $roleName !== '') {
            return $roleName;
        }

        // fallback kalau suatu saat role relation tidak ada, pakai profile relation
        try {
            if (!empty($notifiable->student)) return 'student';
            if (!empty($notifiable->teacher)) return 'teacher';
            if (!empty($notifiable->industry)) return 'industry';
        } catch (\Throwable $e) {
            // ignore
        }

        return 'admin';
    }

    protected function resolveStudentName(): string
    {
        return $this->application->student?->user?->name
            ?? $this->application->student?->name
            ?? 'Siswa';
    }
}
