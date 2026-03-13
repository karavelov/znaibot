<?php

namespace App\DataTables;

use App\Models\Question;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class QuestionDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
        ->addColumn('Действие', function($query) {
            $editBtn = "<a href='" . route('admin.questions.edit', $query->id) . "' class='btn btn-primary'><i class='far fa-edit'></i></a>";
            $deleteBtn = "<a href='" . route('admin.questions.delete', $query->id) . "' class='btn btn-danger ml-2 delete-item'><i class='far fa-trash-alt'></i></a>";

            return $editBtn . $deleteBtn;
        })
        ->rawColumns(['Действие'])
        ->setRowId('id');
    }

    public function query(Question $model): QueryBuilder
    {
        return $model->newQuery();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('question-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->orderBy(0)
                    ->selectStyleSingle()
                    ->buttons([
                        Button::make('excel'),
                        Button::make('csv'),
                        Button::make('pdf'),
                        Button::make('print'),
                        Button::make('reset'),
                        Button::make('reload')
                    ])
                    ->parameters([
                        'language' => [
                            'search' => 'Търсене:',  
                            'emptyTable' => 'Няма налични данни',
                            'info' => 'Показване от _START_ до _END_ от общо _TOTAL_ записа',
                            'infoEmpty' => 'Показване от 0 до 0 от общо 0 записа',
                            'infoFiltered' => '(филтрирани от общо _MAX_ записа)',
                            'lengthMenu' => 'Покажи _MENU_ записа',
                            'loadingRecords' => 'Зареждане...',
                            'processing' => 'Обработка...',
                            'zeroRecords' => 'Няма намерени записи',
                            'paginate' => [
                                'next' => 'Следващ',
                                'previous' => 'Предишен',
                                'first' => 'Първа',
                                'last' => 'Последна'
                            ],
                        ]
                    ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::make('question')->title('Въпрос')->width('50%'),
            Column::make('klas')->title('Клас'),
            Column::make('points')->title('Точки'),
            Column::make('Действие')->exportable(false)->printable(false)->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'Question_' . date('YmdHis');
    }
}