<?php

namespace App\Http\Livewire;

use App\Models\Staff\PlayerRecord;
use App\Models\Staff\Server;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Staff\Player;
use Rappasoft\LaravelLivewireTables\Views\Columns\ButtonGroupColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class PlayerTable extends DataTableComponent
{
    const REFRESH_TIME = 60 * 1000;
    protected $model = Player::class;
    public Server $server;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setRefreshTime(self::REFRESH_TIME);
        $this->setTdAttributes(function(Column $column, $row, $columnIndex, $rowIndex) {
            if ($column->isField('license') || $column->isField('discord')) {
                return [
                    'class' => 'blur hover:blur-none transition',
                ];
            }
            return [];
        });
    }

    public function builder(): Builder
    {
        return Player::query()->where('server_id', $this->server->id);
    }

    public function columns(): array
    {
        return [
            Column::make("Name", "name")
                ->sortable()
                ->searchable(),
            Column::make("License", "license")
                ->searchable(),
            Column::make("Discord", "discord")
                ->searchable(),
            Column::make('Statistics', 'id') // You can adjust the title as needed
                ->format(function ($value, $row, Column $column) {
                    $statistics = PlayerRecord::where('player_id', $row->id)->get();

                    if ($statistics->isNotEmpty()) {
                        $formattedStatistics = [];

                        foreach (PlayerRecord::TYPES as $typeKey => $typeValue) {
                            $count = $statistics->where('type', $typeValue)->count();

                            $formattedStatistics[] = "<label title='$typeKey'>$count" . 'x</label>';
                        }

                        return implode(', ', $formattedStatistics);
                    }

                    return 'Clear Record';
                })->html(),
            Column::make("Online", "online")
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    if ($value) {
                        return '<span class="text-xs font-medium mr-2 px-2.5 py-0.5 rounded bg-green-900 text-green-300">Online</span>';
                    }

                    return '<span class="text-xs font-medium mr-2 px-2.5 py-0.5 rounded bg-red-900 text-red-300">Offline</span>';
                })
                ->html(),
            ButtonGroupColumn::make('Actions')
                ->unclickable()
                ->attributes(function($row) {
                    return [
                        'class' => 'space-x-2',
                    ];
                })
                ->buttons([
                LinkColumn::make('My Link 1')
                    ->title(fn($row) => 'View')
                    ->location(fn($row) => route('staff.server.player', ['server' => $this->server->id, 'player' => $row->license]))
                    ->attributes(function($row) {
                        return [
                            'target' => '_blank',
                            'class' => 'px-4 py-2 bg-purple-500 rounded-md',
                        ];
                    }),
                ]),
        ];
    }

    public function filters(): array
    {
        return [
            SelectFilter::make('Online')
                ->setFilterPillTitle('User Online')
                ->setFilterPillValues([
                    '1' => 'Active',
                    '0' => 'Inactive',
                ])
                ->options([
                    '0' => 'No',
                    '1' => 'Yes',
                ])
                ->filter(function(Builder $builder, string $value) {
                    if ($value === '1') {
                        $builder->where('online', true);
                    } elseif ($value === '0') {
                        $builder->where('online', false);
                    }
                })
        ];
    }
}
