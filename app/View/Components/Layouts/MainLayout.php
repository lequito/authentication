<?php

namespace App\View\Components\Layouts;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MainLayout extends Component{

    public function __construct(
        public string $pageTitle
    ){}

    
    public function render(): View|Closure|string{
        return view('components.layouts.main-layout');
    }
}
