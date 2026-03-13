<?php

namespace App\DataTables;

use App\Models\Subject;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SubjectNameDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('Брой учители', fn (Subject $s) => $s->teachers()->count())
            ->addColumn('Действие', function (Subject $s) {
                $editBtn   = "<a href='" . route('admin.subject-names.edit', $s->id) . "' class='btn btn-primary'><i class='far fa-edit'></i></a>";
                $deleteBtn = "<button class='btn btn-danger ml-2 btn-delete-name'
                                data-id='{$s->id}'
                                data-name='" . e($s->name) . "'
                                data-count='{$s->teachers()->count()}'><i class='far fa-trash-alt'></i></button>";
                return $editBtn . $deleteBtn;
            })
            ->rawColumns(['Действие'])
            ->setRowId('id');
    }

    public function query(Subject $model): QueryBuilder
    {
        return $model->newQuery();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('subject-names-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1, 'asc')
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
                    'search'         => 'Търсене:',
                    'emptyTable'     => 'Няма налични данни',
                    'info'           => 'Показване от _START_ до _END_ от общо _TOTAL_ записа',
                    'infoEmpty'      => 'Показване от 0 до 0 от общо 0 записа',
                    'infoFiltered'   => '(филтрирани от общо _MAX_ записа)',
                    'lengthMenu'     => 'Покажи _MENU_ записа',
                    'loadingRecords' => 'Зареждане...',
                    'processing'     => 'Обработка...',
                    'zeroRecords'    => 'Няма намерени записи',
                    'paginate'       => [
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
            Column::make('name')->title('Предмет'),
            Column::make('Брой учители'),
            Column::computed('Действие')
                ->exportable(false)
                ->printable(false)
                ->width(120)
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'SubjectNames_' . date('YmdHis');
    }
}
