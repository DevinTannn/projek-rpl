<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailService
{
    protected $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        $this->setup();
    }

    protected function setup()
    {
        // Server settings
        $this->mail->isSMTP();
        $this->mail->Host       = env('MAIL_HOST');
        $this->mail->SMTPAuth   = true;
        $this->mail->Username   = env('MAIL_USERNAME');
        $this->mail->Password   = env('MAIL_PASSWORD');
        $this->mail->SMTPSecure = env('MAIL_ENCRYPTION');
        $this->mail->Port       = env('MAIL_PORT');

        // Default set FROM
        $this->mail->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
    }

    public function sendDonationVerified($donation)
    {
        try {
            $this->mail->clearAddresses();
            $this->mail->addAddress($donation->user->email, $donation->user->name);

            $this->mail->isHTML(true);
            $this->mail->Subject = 'Donasi Berhasil Diverifikasi - SumselPeduli';
            
            $amount = number_format($donation->amount, 0, ',', '.');
            $body = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; border: 1px solid #eee; border-radius: 10px; overflow: hidden;'>
                    <div style='background-color: #2D5A27; padding: 20px; text-align: center; color: white;'>
                        <h2 style='margin: 0;'>Terima Kasih, Orang Baik!</h2>
                    </div>
                    <div style='padding: 30px; line-height: 1.6; color: #333;'>
                        <p>Halo, <strong>{$donation->user->name}</strong></p>
                        <p>Kabar baik! Donasi Anda untuk kampanye <strong>'{$donation->campaign->title}'</strong> telah berhasil kami verifikasi.</p>
                        
                        <div style='background-color: #f9f9f9; padding: 20px; border-radius: 8px; margin: 20px 0;'>
                            <table style='width: 100%; border-collapse: collapse;'>
                                <tr>
                                    <td style='padding: 5px 0; color: #777;'>Order ID:</td>
                                    <td style='padding: 5px 0; font-weight: bold; text-align: right;'>#{$donation->order_id}</td>
                                </tr>
                                <tr>
                                    <td style='padding: 5px 0; color: #777;'>Jumlah Donasi:</td>
                                    <td style='padding: 5px 0; font-weight: bold; text-align: right; color: #2D5A27;'>Rp {$amount}</td>
                                </tr>
                                <tr>
                                    <td style='padding: 5px 0; color: #777;'>Metode:</td>
                                    <td style='padding: 5px 0; font-weight: bold; text-align: right;'>Manual Transfer</td>
                                </tr>
                            </table>
                        </div>

                        <p>Semoga bantuan yang Anda berikan menjadi keberkahan dan sangat bermanfaat bagi mereka yang membutuhkan.</p>
                        <p style='margin-top: 30px;'>Salam Hangat,<br><strong>Tim SumselPeduli</strong></p>
                    </div>
                    <div style='background-color: #f4f4f4; padding: 15px; text-align: center; font-size: 12px; color: #999;'>
                        &copy; 2026 SumselPeduli. All rights reserved.
                    </div>
                </div>
            ";

            $this->mail->Body = $body;
            $this->mail->send();
            return true;
        } catch (Exception $e) {
            \Log::error("Email failed: " . $this->mail->ErrorInfo);
            return false;
        }
    }

    public function sendCampaignVerified($campaign)
    {
        try {
            $this->mail->clearAddresses();
            $this->mail->addAddress($campaign->user->email, $campaign->user->name);

            $this->mail->isHTML(true);
            $this->mail->Subject = 'Kampanye Anda Telah Disetujui! - SumselPeduli';
            
            $goal = number_format($campaign->goal_amount, 0, ',', '.');
            $body = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; border: 1px solid #eee; border-radius: 10px; overflow: hidden;'>
                    <div style='background-color: #2D5A27; padding: 20px; text-align: center; color: white;'>
                        <h2 style='margin: 0;'>Kampanye Aktif!</h2>
                    </div>
                    <div style='padding: 30px; line-height: 1.6; color: #333;'>
                        <p>Halo, <strong>{$campaign->user->name}</strong></p>
                        <p>Selamat! Kampanye Anda yang berjudul <strong>'{$campaign->title}'</strong> telah berhasil diverifikasi dan sekarang sudah <strong>AKTIF</strong> di platform SumselPeduli.</p>
                        
                        <div style='background-color: #f9f9f9; padding: 20px; border-radius: 8px; margin: 20px 0;'>
                            <table style='width: 100%; border-collapse: collapse;'>
                                <tr>
                                    <td style='padding: 5px 0; color: #777;'>Target Dana:</td>
                                    <td style='padding: 5px 0; font-weight: bold; text-align: right;'>Rp {$goal}</td>
                                </tr>
                                <tr>
                                    <td style='padding: 5px 0; color: #777;'>Kategori:</td>
                                    <td style='padding: 5px 0; font-weight: bold; text-align: right;'>{$campaign->tag}</td>
                                </tr>
                            </table>
                        </div>

                        <p>Anda sudah bisa mulai membagikan link kampanye Anda kepada calon donatur. Jangan lupa untuk rutin mengupdate perkembangan kampanye agar donatur tetap percaya.</p>
                        
                        <div style='text-align: center; margin: 30px 0;'>
                            <a href='http://127.0.0.1:8001/campaigns/{$campaign->id}' style='background-color: #2D5A27; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold;'>Lihat Kampanye Saya</a>
                        </div>

                        <p style='margin-top: 30px;'>Semoga penggalangan dana Anda berjalan lancar!<br><strong>Tim SumselPeduli</strong></p>
                    </div>
                    <div style='background-color: #f4f4f4; padding: 15px; text-align: center; font-size: 12px; color: #999;'>
                        &copy; 2026 SumselPeduli. All rights reserved.
                    </div>
                </div>
            ";

            $this->mail->Body = $body;
            $this->mail->send();
            return true;
        } catch (Exception $e) {
            \Log::error("Email failed: " . $this->mail->ErrorInfo);
            return false;
        }
    }

    public function sendAccountVerified($verification)
    {
        try {
            $this->mail->clearAddresses();
            $this->mail->addAddress($verification->user->email, $verification->user->name);

            $this->mail->isHTML(true);
            $this->mail->Subject = 'Selamat! Akun Fundraiser Anda Telah Disetujui - SumselPeduli';
            
            $body = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; border: 1px solid #eee; border-radius: 10px; overflow: hidden;'>
                    <div style='background-color: #2D5A27; padding: 20px; text-align: center; color: white;'>
                        <h2 style='margin: 0;'>Selamat Bergabung!</h2>
                    </div>
                    <div style='padding: 30px; line-height: 1.6; color: #333;'>
                        <p>Halo, <strong>{$verification->user->name}</strong></p>
                        <p>Kami dengan senang hati memberitahukan bahwa permohonan Anda untuk menjadi <strong>Fundraiser</strong> di SumselPeduli telah <strong>DISETUJUI</strong>.</p>
                        
                        <p>Sekarang Anda memiliki akses penuh untuk:</p>
                        <ul style='color: #555;'>
                            <li>Membuat kampanye penggalangan dana baru.</li>
                            <li>Mengumpulkan donasi dari masyarakat.</li>
                            <li>Mengunggah perkembangan kampanye (lapangan).</li>
                        </ul>

                        <div style='text-align: center; margin: 30px 0;'>
                            <a href='http://127.0.0.1:8001/login' style='background-color: #2D5A27; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold;'>Login ke Dashboard</a>
                        </div>

                        <p>Terima kasih telah bergabung bersama kami untuk membantu sesama. Mari buat perubahan nyata!</p>
                        <p style='margin-top: 30px;'>Salam Perubahan,<br><strong>Tim SumselPeduli</strong></p>
                    </div>
                    <div style='background-color: #f4f4f4; padding: 15px; text-align: center; font-size: 12px; color: #999;'>
                        &copy; 2026 SumselPeduli. All rights reserved.
                    </div>
                </div>
            ";

            $this->mail->Body = $body;
            $this->mail->send();
            return true;
        } catch (Exception $e) {
            \Log::error("Email failed: " . $this->mail->ErrorInfo);
            return false;
        }
    }

    public function sendAccountRejected($verification)
    {
        try {
            $this->mail->clearAddresses();
            $this->mail->addAddress($verification->user->email, $verification->user->name);

            $this->mail->isHTML(true);
            $this->mail->Subject = 'Pemberitahuan: Verifikasi Akun Fundraiser Ditolak - SumselPeduli';
            
            $body = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; border: 1px solid #eee; border-radius: 10px; overflow: hidden;'>
                    <div style='background-color: #dc3545; padding: 20px; text-align: center; color: white;'>
                        <h2 style='margin: 0;'>Pengajuan Verifikasi Ditolak</h2>
                    </div>
                    <div style='padding: 30px; line-height: 1.6; color: #333;'>
                        <p>Halo, <strong>{$verification->user->name}</strong></p>
                        <p>Terima kasih atas ketertarikan Anda untuk menjadi Fundraiser di SumselPeduli.</p>
                        <p>Setelah melakukan peninjauan terhadap dokumen verifikasi yang Anda kirimkan, mohon maaf kami belum dapat menyetujui pengajuan Anda saat ini karena dokumen tidak memenuhi persyaratan verifikasi.</p>
                        
                        <p>Silakan periksa kembali hal-hal berikut:</p>
                        <ul style='color: #555;'>
                            <li>Kesesuaian nama lengkap dengan kartu identitas (KTP).</li>
                            <li>Kejelasan dan keterbacaan foto identitas (KTP) yang diunggah.</li>
                            <li>Keabsahan nomor NIK.</li>
                        </ul>

                        <div style='text-align: center; margin: 30px 0;'>
                            <a href='http://127.0.0.1:8001/profile/verification' style='background-color: #dc3545; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold;'>Ajukan Ulang Verifikasi</a>
                        </div>

                        <p>Anda dapat mengunggah kembali dokumen yang valid melalui menu Verifikasi di profil Anda.</p>
                        <p style='margin-top: 30px;'>Salam Hangat,<br><strong>Tim SumselPeduli</strong></p>
                    </div>
                    <div style='background-color: #f4f4f4; padding: 15px; text-align: center; font-size: 12px; color: #999;'>
                        &copy; 2026 SumselPeduli. All rights reserved.
                    </div>
                </div>
            ";

            $this->mail->Body = $body;
            $this->mail->send();
            return true;
        } catch (Exception $e) {
            \Log::error("Email failed: " . $this->mail->ErrorInfo);
            return false;
        }
    }
}
