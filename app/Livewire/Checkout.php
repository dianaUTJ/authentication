<?php

namespace App\Livewire;

use App\Filament\Resources\ProductResource;
use App\Models\User;
use Livewire\Component;
use Laravel\Cashier\Cashier;
use Illuminate\Http\Request;



class Checkout extends Component
{

    public function render()
    {
        return view('livewire.checkout');
    }





}
