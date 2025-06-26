<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Support\Facades\Log;

/**
 * V.E.G.A. - Visual Exploration and Graphical Analysis of Asteroids
 * Copyright (c) 2025 Manuel Carlucci
 *
 * This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License.
 * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/4.0/
 */

class FavoriteAsteroidUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $asteroid_id;
    protected $designation;
    protected $message;

    /**
     * Create a new notification instance.
     *
     * @param  \App\Models\FavoriteAsteroid  $asteroid
     * @param  string  $message
     * @return void
     */
    public function __construct($asteroid, $changes)
    {
        Log::info('Costruttore FavoriteAsteroidUpdated', [
            'asteroid' => $asteroid->toArray(),
            'changes' => $changes
        ]);

        $this->asteroid_id = $asteroid->asteroid_id;
        $this->designation = $asteroid->asteroid_designation;
        $this->message = $changes;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database']; // Memorizziamo la notifica nel database
    }

    /**
     * Get the array representation of the notification to store in database.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'asteroid_id' => $this->asteroid_id,
            'designation' => $this->designation,
            'message' => $this->message,
            'notified_at' => now(),
        ];
    }
}
