<?php

namespace App\DataTables;

use App\Models\Club;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ClubDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('Действие', function ($club) {
                $editBtn   = "<a href='" . route('admin.clubs.edit', $club->id) . "' class='btn btn-primary'><i class='far fa-edit'></i></a>";
                $deleteBtn = "<a href='" . route('admin.clubs.delete', $club->id) . "' class='btn btn-danger ml-2 delete-item'><i class='far fa-trash-alt'></i></a>";
                return $editBtn . $deleteBtn;
            })
            ->addColumn('Брой членове', fn ($club) => $club->students_count)
            ->addColumn('Иконка', function ($club) {
                return "<img width='60px' height='60px' style='object-fit:contain' src='" . asset($club->icon) . "'>";
            })
            ->addColumn('Статус', function ($club) {
                if ($club->status == 1) {
                    return '<label class="custom-switch mt-2">
                        <input type="checkbox" checked name="custom-switch-checkbox" data-id="' . $club->id . '" class="custom-switch-input change-status">
                        <span class="custom-switch-indicator"></span>
                    </label>';
                } else {
                    return '<label class="custom-switch mt-2">
                        <input type="checkbox" name="custom-switch-checkbox" data-id="' . $club->id . '" class="custom-switch-input change-status">
                        <span class="custom-switch-indicator"></span>
                    </label>';
                }
            })
            ->rawColumns(['Действие', 'Иконка', 'Статус', 'Брой членове'])
            ->setRowId('id');
    }

    public function query(Club $model): QueryBuilder
    {
        return $model->newQuery()->withCount('students');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('clubs-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0, 'desc')
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel'),
                Button::make('csv'),
                Button::make('pdf'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload'),
            ])
            ->parameters([
                'language' => [
                    'search'       => 'Търсене:',
                    'emptyTable'   => 'Няма налични данни',
                    'info'         => 'Показване от _START_ до _END_ от общо _TOTAL_ записа',
                    'infoEmpty'    => 'Показване от 0 до 0 от общо 0 записа',
                    'infoFiltered' => '(филтрирани от общо _MAX_ записа)',
                    'lengthMenu'   => 'Покажи _MENU_ записа',
                    'loadingRecords' => 'Зареждане...',
                    'processing'   => 'Обработка...',
                    'zeroRecords'  => 'Няма намерени записи',
                    'paginate'     => [
                        'next'     => 'Следващ',
                        'previous' => 'Предишен',
                        'first'    => 'Първа',
                        'last'     => 'Последна',
                    ],
                ],
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::make('Иконка'),
            Column::make('name')->title('Име на клуб'),
            Column::make('Брой членове'),
            Column::make('Статус'),
            Column::computed('Действие')
                ->exportable(false)
                ->printable(false)
                ->width(120)
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Clubs_' . date('YmdHis');
    }
}
