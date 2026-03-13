<?php

namespace App\DataTables;

use App\Models\Allergen;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class AllergenDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('Наименование', function (Allergen $a) {
                $dot = "<span style='display:inline-block;width:12px;height:12px;border-radius:50%;background:{$a->color};margin-right:6px;vertical-align:middle;'></span>";
                return $dot . e($a->name);
            })
            ->addColumn('Описание', fn (Allergen $a) => $a->description ?? '—')
            ->addColumn('Засегнати', fn (Allergen $a) => $a->users_count)
            ->addColumn('Действие', function (Allergen $a) {
                $edit = route('admin.allergens.edit', $a->id);
                $del  = route('admin.allergens.destroy', $a->id);
                return "
                    <a href='{$edit}' class='btn btn-sm btn-primary'>
                        <i class='fas fa-edit'></i>
                    </a>
                    <button class='btn btn-sm btn-danger btn-delete-allergen'
                        data-id='{$a->id}'
                        data-name='" . e($a->name) . "'
                        data-count='{$a->users_count}'
                        data-url='{$del}'>
                        <i class='fas fa-trash'></i>
                    </button>";
            })
            ->rawColumns(['Наименование', 'Действие'])
            ->setRowId('id');
    }

    public function query(Allergen $model): QueryBuilder
    {
        return $model->newQuery()->withCount('users');
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('allergens-table')
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
                        'next' => 'Следващ', 'previous' => 'Предишен',
                        'first' => 'Първа',  'last' => 'Последна',
                    ],
                ],
            ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::make('Наименование'),
            Column::make('Описание'),
            Column::make('Засегнати'),
            Column::make('Действие')->orderable(false)->searchable(false),
        ];
    }

    protected function filename(): string
    {
        return 'Allergens_' . date('YmdHis');
    }
}
