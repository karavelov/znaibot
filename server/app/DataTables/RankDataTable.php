<?php

namespace App\DataTables;

use App\Models\Rank;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class RankDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
        ->addColumn('Действие', function ($query) {
            $editBtn = "<a href='" . route('admin.rank.edit', $query->id) . "' class='btn btn-primary'><i class='far fa-edit'></i></a>";
            $deleteBtn = "<a href='" . route('admin.rank.delete', $query->id) . "' class='btn btn-danger ml-2 delete-item'><i class='far fa-trash-alt'></i></a>";

            return $editBtn . $deleteBtn;
        })
        ->addColumn('image', function($query) {
            return '<img src="'.asset($query->image).'" width="100" />';
        })
        ->rawColumns(['image', 'Действие'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Rank $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('rank-table')
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
            Column::make('id')->width(100),
            Column::make('image')->title('Изображение'),
            Column::make('title')->title('Име')->width(500),
            Column::computed('Действие')
                ->exportable(false)
                ->printable(false)
                ->width(200)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Rank_' . date('YmdHis');
    }
}
