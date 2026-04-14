<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Filament\Schemas\Schema;
use Illuminate\Contracts\Support\Htmlable;

class Login extends BaseLogin
{
    public function authenticate(): ?LoginResponse
    {
        $response = parent::authenticate();

        if ($response !== null) {
            $user = auth()->user();
            if ($user && method_exists($user, 'logActivity')) {
                $user->logActivity('Prijavljen u admin panel');
            }
        }

        return $response;
    }

    public function getHeading(): string | Htmlable | null
    {
        return '';
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getEmailFormComponent()
                    ->label('E-mail'),
                $this->getPasswordFormComponent(),
            ]);
    }
}
