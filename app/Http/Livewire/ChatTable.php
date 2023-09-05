<?php

namespace App\Http\Livewire;

use App\Models\Staff\Player;
use App\Models\Staff\Server;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Staff\ServerChat;

class ChatTable extends DataTableComponent
{
    const REFRESH_TIME = 30 * 1000;
    protected $model = ServerChat::class;
    public Server $server;

    public function configure(): void
    {
        $this->setPrimaryKey('server_id');
        $this->setRefreshTime(self::REFRESH_TIME);
    }

    public function builder(): Builder
    {
        return ServerChat::query()->where('server_id', $this->server->id);
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
            Column::make("Message", "message")
                ->sortable(),
            Column::make("Created at", "created_at")
                ->sortable()
            ->format(function ($value, $row, Column $column) {
                return '<span title="' . $value . '">' . $row->created_at->diffForHumans() . '</span>';
            })->html(),
        ];
    }
}
