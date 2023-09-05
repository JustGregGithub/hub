<?php

namespace App\Http\Livewire;

use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Staff\ServerTimeclock;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class TimeclockTable extends DataTableComponent
{
    protected $model = ServerTimeclock::class;
    public $server;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function builder(): Builder
    {
        return ServerTimeclock::query()->where('server_id', $this->server->id);
    }

    public function columns(): array
    {
        return [
            Column::make("Username", "user_id")
                ->sortable()
                ->searchable()
                ->format(function ($value, $row, Column $column) {
                    static $cache = [];

                    if (isset($cache[$value])) return $cache[$value];

                    $user = User::where('id', $value)->first();
                    if ($user) {
                        $cache[$value] = $user->displayName();
                        return $user->displayName();
                    }
                }),
            Column::make("Type", "type")
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    switch ($value)
                    {
                        case ServerTimeclock::TYPES['clock_in']:
                            return '<span class="text-xs font-medium mr-2 px-2.5 py-0.5 rounded bg-green-900 text-green-300">Clock In</span>';
                        case ServerTimeclock::TYPES['clock_out']:
                            return '<span class="text-xs font-medium mr-2 px-2.5 py-0.5 rounded bg-red-900 text-red-300">Clock out</span>';
                        case ServerTimeclock::TYPES['auto_clock_out']:
                            return '<span class="text-xs font-medium mr-2 px-2.5 py-0.5 rounded bg-yellow-900 text-yellow-300">AFK Clock Out</span>';
                    }
                })->html(),
            Column::make("Time", "time")
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    if ($row->type === ServerTimeclock::TYPES['clock_out'] || $row->type === ServerTimeclock::TYPES['auto_clock_out'])
                        return CarbonInterval::minutes($value)->cascade()->forHumans();
                })->html(),
            Column::make("Date", "created_at")
                ->sortable()
        ];
    }

    public function filters(): array
    {
        return [
            SelectFilter::make('Type')
                ->setFilterPillTitle('Type')
                ->setFilterPillValues([
                    '0' => 'Clock In',
                    '1' => 'Clock Out',
                    '2' => 'AFK Clock Out',
                ])
                ->options([
                    ServerTimeclock::TYPES['clock_in'] => 'Clock In',
                    ServerTimeclock::TYPES['clock_out'] => 'Clock Out',
                    ServerTimeclock::TYPES['auto_clock_out'] => 'AFK Clock Out',
                ])
                ->filter(function(Builder $builder, string $value) {
                    $builder->where('type', $value);
                }),
            SelectFilter::make('User')
                ->setFilterPillTitle('User')
                ->setFilterPillValues([
                    '0' => 'Clock In',
                    '1' => 'Clock Out',
                    '2' => 'AFK Clock Out',
                ])
                ->options(User::all()->mapWithKeys(function ($user) {
                    return [$user->id => $user->displayName()];
                })->toArray())
                ->filter(function(Builder $builder, string $value) {
                    $builder->where('user_id', $value);
                }),
        ];
    }
}
