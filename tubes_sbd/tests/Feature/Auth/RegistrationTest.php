<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Notifications\VerifyPlayMartEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        Notification::fake();

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('verification.notice', absolute: false));

        $user = User::where('email', 'test@example.com')->firstOrFail();

        $this->assertFalse($user->hasVerifiedEmail());
        Notification::assertSentTo(
            $user,
            VerifyPlayMartEmail::class,
            function (VerifyPlayMartEmail $notification) use ($user): bool {
                $mail = $notification->toMail($user);

                return $mail->subject === 'Verifikasi Email PlayMart'
                    && ($mail->view['html'] ?? null) === 'emails.auth.verify-email'
                    && ($mail->view['text'] ?? null) === 'emails.auth.verify-email-text'
                    && ($mail->viewData['appName'] ?? null) === 'PlayMart';
            }
        );
    }
}
