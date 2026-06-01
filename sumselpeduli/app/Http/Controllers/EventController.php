<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EventController extends Controller
{
    public function show($type)
    {
        // For now, support ramadan, natal, imlek
        $events = [
            'ramadan' => [
                'title' => 'Ramadan Peduli',
                'description' => 'Bulan penuh berkah, mari perbanyak sedekah dan berbagi kebaikan.',
                'theme' => 'ramadan',
                'bg_color' => '#1b4d3e',
                'accent_color' => '#c2a83e',
                'icon' => 'moon'
            ],
            'natal' => [
                'title' => 'Kebaikan Natal',
                'description' => 'Berbagi sukacita dan damai di hari yang suci.',
                'theme' => 'natal',
                'bg_color' => '#8b0000',
                'accent_color' => '#2e8b57',
                'icon' => 'gift'
            ],
            'imlek' => [
                'title' => 'Imlek Berbagi',
                'description' => 'Rayakan kebersamaan dan kemakmuran dengan berbagi angpao kebaikan.',
                'theme' => 'imlek',
                'bg_color' => '#d40000',
                'accent_color' => '#ffd700',
                'icon' => 'award'
            ]
        ];

        $event = $events[$type] ?? $events['ramadan'];

        return view('events.show', compact('event'));
    }
}
