<?php

namespace App\DataTables;

use App\Models\Quiz;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class QuizDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
        // ->addColumn('public', function ($query) {
        //     if ($query->public == 1) {
        //         $button = '<label class="custom-switch mt-2">
        //         <input type="checkbox" checked name="custom-switch-checkbox" data-id="' . $query->id . '" class="custom-switch-input change-public" >
        //         <span class="custom-switch-indicator"></span>
        //     </label>';
        //     } else {
        //         $button = '<label class="custom-switch mt-2">
        //         <input type="checkbox" name="custom-switch-checkbox" data-id="' . $query->id . '" class="custom-switch-input change-public">
        //         <span class="custom-switch-indicator"></span>
        //     </label>';
        //     }
        //     return $button;
        // })
        ->addColumn('Статус', function ($query) {
            if ($query->published == 1) {
                $button = '<label class="custom-switch mt-2">
                <input type="checkbox" checked name="custom-switch-checkbox" data-id="' . $query->id . '" class="custom-switch-input change-status" >
                <span class="custom-switch-indicator"></span>
            </label>';
            } else {
                $button = '<label class="custom-switch mt-2">
                <input type="checkbox" name="custom-switch-checkbox" data-id="' . $query->id . '" class="custom-switch-input change-status">
                <span class="custom-switch-indicator"></span>
            </label>';
            }
            return $button;
        })
    ->addColumn('Questions Count', function($query) {
        return $query->questions_count;
    })
    ->addColumn('Действие', function($query){
        $editBtn = "<a href='".route('admin.quizzes.edit', $query->id)."' class='btn btn-primary'><i class='far fa-edit'></i></a>";               
        $deleteBtn = "<a href='".route('admin.quizzes.delete', $query->id)."' class='btn btn-danger ml-2 delete-item'><i class='far fa-trash-alt'></i></a>";

        return $editBtn.$deleteBtn;
    })
    ->rawColumns(['Статус', 'Действие'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Quiz $model): QueryBuilder
    {
        return $model->newQuery()->withCount('questions');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('quiz-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    //->dom('Bfrtip')
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

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::make('title')->title('Заглавие'),
            Column::make('questions_count')->title('Брой въпроси'),
            // Column::make('public')->title('Публичен'),
            Column::make('Статус'),
            Column::make('Действие'),

        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Quiz_' . date('YmdHis');
    }
}
