<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Card extends Component
{
    public $title;
    public $value;
    public $valueClass;
    public $suffix;
    public $prefix;
    public $borderColor;
    public $icon;
    /**
     * Create a new component instance.
     */
    public function __construct($title, $value, $borderColor, $icon, $suffix = '', $prefix = '', $valueClass = '')
    {
        $this->title = $title;
        $this->value = $value;
        $this->borderColor = $borderColor;
        $this->icon = $icon;
        $this->suffix = $suffix;
        $this->prefix = $prefix;
        $this->valueClass = $valueClass;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.card');
    }
}
