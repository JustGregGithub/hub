<?php

namespace App\Http\Livewire;

use App\Models\Staff\Player;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Staff\ServerReport;

class ReportTable extends DataTableComponent
{
    const REFRESH_TIME = 30 * 1000;
    protected $model = ServerReport::class;
    public $server;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setRefreshTime(self::REFRESH_TIME);
    }

    public function builder(): Builder
    {
        return ServerReport::query()->where('server_id', $this->server->id);
    }

    public function columns(): array
    {
        return [
            Column::make("Reporter", "player_id")
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    $player = Player::where('id', $value)->first();
                    return '<a class="cursor-pointer hover:text-blue-500 transition" href="' . route('staff.server.player', ['server' => $this->server, 'player' => $player->license]) . '">' . $player->name . '</a>';
                })->html(),
            Column::make("Reported", "reported_id")
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    $player = Player::where('id', $value)->first();
                    return '<a class="cursor-pointer hover:text-blue-500 transition" href="' . route('staff.server.player', ['server' => $this->server, 'player' => $player->license]) . '">' . $player->name . '</a>';
                })->html(),
            Column::make("Reason", "reason")
                ->sortable(),
            Column::make("Time", "created_at")
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    return '<span title="' . $value . '">' . $row->created_at->diffForHumans() . '</span>';
                })->html(),
        ];
    }
}
