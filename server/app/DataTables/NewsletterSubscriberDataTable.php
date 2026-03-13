<?php

namespace App\DataTables;

use App\Models\NewsletterSubscriber;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class NewsletterSubscriberDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('Действие', function($query){
                $deleteBtn = "<a href='".route('admin.subscribers.delete', $query->id)."' class='btn btn-danger ml-2 delete-item'><i class='far fa-trash-alt'></i></a>";

                return $deleteBtn;
            })
            ->addColumn('Потвърден_имейл', function($query){
                if($query->is_verified == 1){

                    return '<i class="badge bg-success text-light">Да</i>';
                }else {
                    return '<i class="badge bg-danger text-light">Не</i>';
                }
            })
            ->rawColumns(['Действие', 'Потвърден_имейл'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(NewsletterSubscriber $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('newslettersubscriber-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    //->dom('Bfrtip')
                    ->orderBy(1)
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
            Column::make('email')->title('Имейл'),
            Column::make('Потвърден_имейл'),
            Column::computed('Действие')
            ->exportable(false)
            ->printable(false)
            ->width(60)
            ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'NewsletterSubscriber_' . date('YmdHis');
    }
}