<?php

namespace App\View\Components\Forms;

use Illuminate\View\Component;

class InsertNewValue extends Component
{

    public string $storeRoute;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($storeRoute)
    {
        $this->storeRoute = $storeRoute;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.forms.insert-new-value');
    }
}
