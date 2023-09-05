<?php

namespace App\Http\Livewire;

use App\Models\Staff\PlayerBan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Staff\PlayerRecord;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class RecordsTable extends DataTableComponent
{
    public $server;
    public $player;

    public function mount($server, $player)
    {
        $this->server = $server;
        $this->player = $player;
    }

    public function builder(): Builder
    {
        return PlayerRecord::query()
            ->where('server_id', $this->server->id)
            ->where('player_id', $this->player->id);
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Type", "type")
                ->sortable()
                ->format(function ($value, $row, Column $column) {
                    return match ($value) {
                        PlayerRecord::TYPES['Warn'] => '<span class="text-xs font-medium mr-2 px-2.5 py-0.5 rounded bg-yellow-900 text-yellow-300">Warn</span>',
                        PlayerRecord::TYPES['Kick'] => '<span class="text-xs font-medium mr-2 px-2.5 py-0.5 rounded bg-blue-900 text-blue-300">Kick</span>',
                        PlayerRecord::TYPES['Ban'] => '<span class="text-xs font-medium mr-2 px-2.5 py-0.5 rounded bg-red-900 text-red-300">Ban</span>',
                        PlayerRecord::TYPES['Note'] => '<span class="text-xs font-medium mr-2 px-2.5 py-0.5 rounded bg-gray-700 text-gray-300">Note</span>',
                        PlayerRecord::TYPES['Commend'] => '<span class="text-xs font-medium mr-2 px-2.5 py-0.5 rounded bg-green-900 text-green-300">Commend</span>',
                        default => '<span class="text-xs font-medium mr-2 px-2.5 py-0.5 rounded bg-gray-700 text-gray-300">Unknown Type</span>',
                    };
                })
                ->html(),
            Column::make("Message", "message")
                ->sortable()
                ->searchable(),
            Column::make("Expires", "type")
                ->format(function ($value, $row, Column $column) {
                    if ($value == PlayerRecord::TYPES['Ban']) {
                        $banDetails = PlayerRecord::where([
                            'type' => PlayerRecord::TYPES['Ban'],
                            'message' => $row['message'],
                            'staff_id' => $row['staff_id']
                        ])->orderByDesc('expiration_date')->first();

                        if ($banDetails) {
                            if ($banDetails->expiration_date === null) {
                                return 'Permanent';
                            }

                            $expirationDate = Carbon::parse($banDetails->expiration_date);

                            if ($expirationDate->isFuture()) {
                                return 'Active until ' . $expirationDate->format('Y-m-d H:i:s');
                            }

                            if ($expirationDate->isPast()) {
                                return 'Expired - ' . $expirationDate->format('Y-m-d H:i:s');
                            }

                            return 'Permanent';
                        }
                    }

                    return '';
                }),

            Column::make("Staff Member", "staff_id")
                ->format(function ($value, $row, Column $column) {
                    $staff = User::where('id', $value)->first();
                    return '<p class="cursor-pointer hover:text-blue-500 transition" data-tooltip-target="tooltip-copy" x-data="{ id: \'' . $staff->id . '\' }" @click="$clipboard(id); alert(\'Successfully Copied Discord ID To Clipboard.\')">' . $staff->displayName() . '</p>';
                })
                ->html(),
        ];
    }

    public function filters(): array
    {
        return [
            SelectFilter::make('Type')
                ->setFilterPillTitle('Type')
                ->setFilterPillValues([
                    PlayerRecord::TYPES['Warn'] => 'Warn',
                    PlayerRecord::TYPES['Kick'] => 'Kick',
                    PlayerRecord::TYPES['Ban'] => 'Ban',
                    PlayerRecord::TYPES['Note'] => 'Note',
                    PlayerRecord::TYPES['Commend'] => 'Commend',
                ])
                ->options([
                    PlayerRecord::TYPES['Warn'] => 'Warn',
                    PlayerRecord::TYPES['Kick'] => 'Kick',
                    PlayerRecord::TYPES['Ban'] => 'Ban',
                    PlayerRecord::TYPES['Note'] => 'Note',
                    PlayerRecord::TYPES['Commend'] => 'Commend',
                ])
                ->filter(function(Builder $builder, string $value) {
                    $builder->where('type', $value);
                })
        ];
    }
}
