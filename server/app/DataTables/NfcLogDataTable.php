<?php

namespace App\DataTables;

use App\Models\NfcLog;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class NfcLogDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('Потребител', function (NfcLog $log) {
                if ($log->user) {
                    $name = e($log->user->name);
                    $url  = route('admin.users.edit', $log->user->id);
                    return "<a href='{$url}'>{$name}</a>";
                }
                return '<span class="text-muted">Непознат чип</span>';
            })
            ->addColumn('Четец', function (NfcLog $log) {
                if (!$log->nfcReader) {
                    return '<span class="badge badge-secondary">—</span>';
                }
                $typeMap = [
                    'door_in'  => 'badge-success',
                    'door_out' => 'badge-danger',
                    'robot'    => 'badge-info',
                    'other'    => 'badge-secondary',
                ];
                $class = $typeMap[$log->nfcReader->type] ?? 'badge-secondary';
                $title = e($log->nfcReader->title);
                return "<span class='badge {$class}'>{$title}</span>";
            })
            ->addColumn('Дата и час', fn (NfcLog $log) => $log->read_at->format('d.m.Y H:i:s'))
            ->rawColumns(['Потребител', 'Четец'])
            ->setRowId('id');
    }

    public function query(NfcLog $model): QueryBuilder
    {
        $query = $model->newQuery()
            ->with(['user', 'nfcReader'])
            ->orderBy('read_at', 'desc');

        if (request('reader_filter')) {
            $query->where('nfc_reader_id', request('reader_filter'));
        }

        if (request('date_filter')) {
            $query->whereDate('read_at', request('date_filter'));
        }

        return $query;
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('nfc-logs-table')
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
            Column::make('nfc_id')->title('NFC ID'),
            Column::make('Потребител'),
            Column::make('Четец'),
            Column::make('Дата и час'),
        ];
    }

    protected function filename(): string
    {
        return 'NfcLogs_' . date('YmdHis');
    }
}
