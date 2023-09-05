<?php

namespace App\Http\Livewire;

use App\Models\Staff\Player;
use App\Models\Staff\ServerChat;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Staff\ServerDeath;

class DeathTable extends DataTableComponent
{
    const REFRESH_TIME = 30 * 1000;
    protected $model = ServerDeath::class;
    public $server;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function builder(): Builder
    {
        return ServerDeath::query()->where('server_id', $this->server->id);
    }

    public function columns(): array
    {
        return [
            Column::make("Player", "player_id")
                ->sortable()
            ->format(function ($value, $row, Column $column) {
                $player = Player::where('id', $value)->first();
                return '<a class="cursor-pointer hover:text-blue-500 transition" href="' . route('staff.server.player', ['server' => $this->server, 'player' => $player->license]) . '">' . $player->name . '</a>';
            })->html(),
            Column::make("Killer", "killer_id")
                ->sortable()
            ->format(function ($value, $row, Column $column) {
                $player = Player::where('id', $value)->first();
                return '<a class="cursor-pointer hover:text-blue-500 transition" href="' . route('staff.server.player', ['server' => $this->server, 'player' => $player->license]) . '">' . $player->name . '</a>';
            })->html(),
            Column::make("Cause", "cause")
                ->sortable(),
            Column::make("Created at", "created_at")
                ->sortable(),
            Column::make("Updated at", "updated_at")
                ->sortable(),
        ];
    }
}
