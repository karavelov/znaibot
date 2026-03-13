<?php

namespace App\DataTables;

use App\Models\SubjectTeacher;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SubjectDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('Предмет', fn (SubjectTeacher $row) => $row->subject->name ?? '—')
            ->addColumn('Учител',  fn (SubjectTeacher $row) => $row->teacher->name  ?? '—')
            ->addColumn('Действие', function (SubjectTeacher $row) {
                $editBtn   = "<a href='" . route('admin.subjects.edit', $row->id) . "' class='btn btn-primary'><i class='far fa-edit'></i></a>";
                $deleteBtn = "<button class='btn btn-danger ml-2 btn-delete-subject' data-id='{$row->id}' data-subject='{$row->subject->name}' data-teacher='{$row->teacher->name}'><i class='far fa-trash-alt'></i></button>";
                return $editBtn . $deleteBtn;
            })
            ->rawColumns(['Действие'])
            ->setRowId('id');
    }

    public function query(SubjectTeacher $model): QueryBuilder
    {
        return $model->newQuery()->with(['subject', 'teacher']);
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('subjects-table')
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
            Column::make('Предмет'),
            Column::make('Учител'),
            Column::computed('Действие')
                ->exportable(false)
                ->printable(false)
                ->width(120)
                ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Subjects_' . date('YmdHis');
    }
}
