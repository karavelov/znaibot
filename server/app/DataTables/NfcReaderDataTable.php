<?php

namespace App\DataTables;

use App\Models\NfcReader;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class NfcReaderDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('Тип', fn (NfcReader $r) => $r->type_label)
            ->addColumn('Брой логове', fn (NfcReader $r) => $r->logs_count)
            ->addColumn('Действие', function (NfcReader $r) {
                $edit = route('admin.nfc-readers.edit', $r->id);
                $del  = route('admin.nfc-readers.destroy', $r->id);
                return "
                    <a href='{$edit}' class='btn btn-sm btn-primary'>
                        <i class='fas fa-edit'></i>
                    </a>
                    <button class='btn btn-sm btn-danger btn-delete-reader'
                        data-id='{$r->id}'
                        data-title='" . e($r->title) . "'
                        data-url='{$del}'>
                        <i class='fas fa-trash'></i>
                    </button>";
            })
            ->rawColumns(['Действие'])
            ->setRowId('id');
    }

    public function query(NfcReader $model): QueryBuilder
    {
        return $model->newQuery()->withCount('logs');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('nfc-readers-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0)
            ->selectStyleSingle()
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
            Column::make('title')->title('Заглавие'),
            Column::make('Тип'),
            Column::make('Брой логове'),
            Column::make('Действие')->orderable(false)->searchable(false),
        ];
    }

    protected function filename(): string
    {
        return 'NfcReaders_' . date('YmdHis');
    }
}
