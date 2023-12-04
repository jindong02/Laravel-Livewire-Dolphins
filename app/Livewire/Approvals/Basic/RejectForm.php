<?php

namespace App\Livewire\Approvals\Basic;

use Livewire\Attributes\Rule;
use Livewire\Component;

class RejectForm extends Component
{

    #[Rule(['required', 'boolean'])]
    public $is_allowed_to_update = 1;

    #[Rule('required')]
    public $remarks = '';

    /**
     * Reject Items
     */
    public function rejectItems()
    {
        $this->validate();

        $this->dispatch('item-rejected', $this->remarks, $this->is_allowed_to_update);
    }

    public function render()
    {
        return view('livewire.approvals.basic.reject-form');
    }
}
