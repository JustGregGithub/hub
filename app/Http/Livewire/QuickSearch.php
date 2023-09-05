<?php

namespace App\Http\Livewire;

use App\Models\Staff\Player;
use App\Models\Staff\Server;
use Exception;
use Http;
use Illuminate\Support\Facades\Crypt;
use Livewire\Component;

class QuickSearch extends Component
{
    public Server $server;
    public $player_id;
    public $response;
    public $userStartedTyping;

    public function mount(Server $server)
    {
        $this->server = $server;
        $this->userStartedTyping = false;
    }

    //on input change
    public function updatedPlayerId()
    {
        //if it is empty
        if (empty($this->player_id)) {
            $this->response = null;
            $this->userStartedTyping = false;
            return;
        }

        $this->userStartedTyping = true;

        try {
            $response = Http::timeout(3)->withHeaders([
                'secret' => Crypt::decryptString($this->server->secret),
                'id' => $this->player_id,
            ])->get("http://{$this->server->ip}:{$this->server->port}/staffpanel/search");

            if ($response->successful()) {
                $json = $response->json();
                $this->response = Player::where('license', $json['license'])->first();
            } else {
                $this->response = null;
            }
        } catch (Exception $e) {
            $this->response = null;
        }
    }

    public function render()
    {
        return view('livewire.quick-search');
    }
}
