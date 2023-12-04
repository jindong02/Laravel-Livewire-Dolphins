<?php

namespace App\Livewire\Auth;

use Livewire\Component;

class LogoutForm extends Component
{

    public function logout(): void
    {
        auth()->guard('web')->logout();

        session()->invalidate();
        session()->regenerateToken();

        $this->redirect('/', navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.logout-form');
    }
}
