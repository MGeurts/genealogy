<?php

namespace App\View\Components;

class Button extends \TallStackUi\View\Components\Button\Button
{
    public function backgroundColor(): array
    {
        return [
            'solid' => [
                // other indexes goes here ...

                'success' => 'bg-success-500 border-success-500 hover:bg-success-600 hover:border-success-600',
                'danger' => 'bg-danger-500 border-danger-500 hover:bg-danger-600 hover:border-danger-600',
                'warning' => 'bg-warning-500 border-warning-500 hover:bg-warning-600 hover:border-warning-600',
                'info' => 'bg-info-500 border-info-500 hover:bg-info-600 hover:border-info-600',
            ],
            'outline' => [
                // other indexes goes here

                'success' => 'bg-transparent border-success-500',
                'danger' => 'bg-transparent border-danger-500',
                'warning' => 'bg-transparent border-warning-500',
                'info' => 'bg-transparent border-info-500',
            ],
            'light' => [
                // other indexes goes here

                'success' => 'bg-success-300 border-success-300',
                'danger' => 'bg-danger-300 border-danger-300',
                'warning' => 'bg-warning-300 border-warning-300',
                'info' => 'bg-info-300 border-info-300',
            ],
        ];
    }
}
