<?php

namespace App\Console\Commands;

use App\Models\Ticket;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CreateTicket extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-ticket';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        for ($i=0; $i<10; $i++) {
            //Create a ticket with a random title and content
            $ticket = Ticket::create([
                'user_id' => 398514225435377673,
                'category_id' => 7,
                'title' => Str::random(10),
                'content' => Str::random(50),
            ]);

            // Add slug and save.
            $ticket->slug = Str::slug($ticket->title . '-' . $ticket->id);
            $ticket->save();
        }
    }
}
