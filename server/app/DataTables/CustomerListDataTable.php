<?php

namespace App\DataTables;

use App\Models\CustomerList;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class CustomerListDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('Статус', function($query){

                if(Auth::user()->id != $query->id || $query->id != 1){
                if($query->status == 'active'){
                    $button = '<label class="custom-switch mt-2">
                        <input type="checkbox" checked name="custom-switch-checkbox" data-id="'.$query->id.'" class="custom-switch-input change-status" >
                        <span class="custom-switch-indicator"></span>
                    </label>';
                }else {
                    $button = '<label class="custom-switch mt-2">
                        <input type="checkbox" name="custom-switch-checkbox" data-id="'.$query->id.'" class="custom-switch-input change-status">
                        <span class="custom-switch-indicator"></span>
                    </label>';
                }
                return $button;
            }})
            ->addColumn('Действие', function($query){
                if(Auth::user()->id != $query->id || $query->id != 1){
                $editBtn = "<a href='".route('admin.users.edit', $query->id)."' class='btn btn-primary'><i class='far fa-edit'></i></a>";               
                $deleteBtn = "<a href='".route('admin.users.delete', $query->id)."' class='btn btn-danger ml-2 delete-item'><i class='far fa-trash-alt'></i></a>";
                }else{
                    // disable buttons for logged in user or id=1
                    $editBtn = "<button class='btn btn-primary' title='НЕ МОЖЕ ДА РЕДАКТИРАТЕ СЕБЕ СИ!' disabled><i class='far fa-edit'></i></button>";
                    $deleteBtn = "<button class='btn btn-danger ml-2' title='НЕ МОЖЕ ДА ИЗТРИЕТЕ СЕБЕ СИ!' disabled><i class='far fa-trash-alt'></i></button>";
                }

                return $editBtn.$deleteBtn;
            })
            ->addColumn('Активност', function($query) {
                if($query->last_login_at){
                    $last_login_at= \Carbon\Carbon::parse($query->last_login_at);
                    return $last_login_at->diffForHumans();
                }
            })
            ->addColumn('Роля', function($query){
                $roles = [
                    'admin'    => ['label' => 'Администратор', 'class' => 'badge-success'],
                    'user'     => ['label' => 'Потребител',    'class' => 'badge-primary'],
                    'student'  => ['label' => 'Ученик',        'class' => 'badge-info'],
                    'teacher'  => ['label' => 'Учител',        'class' => 'badge-warning'],
                    'parent'   => ['label' => 'Родител',       'class' => 'badge-secondary'],
                    'security' => ['label' => 'Охрана',        'class' => 'badge-danger'],
                ];
                $role = $roles[$query->role] ?? ['label' => $query->role, 'class' => 'badge-light'];
                return '<span class="badge '.$role['class'].'">'.$role['label'].'</span>';
            })
            ->rawColumns(['Статус', 'Действие', 'Роля'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(User $model): QueryBuilder
    {
        $query = $model->newQuery();

        if (request('role_filter')) {
            $query->where('role', request('role_filter'));
        }

        return $query;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('customerlist-table')
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
                        'initComplete' => "function() {
                            var roleSelect = '<select id=\"role-filter\" class=\"form-control form-control-sm ml-2\" style=\"display:inline-block;width:auto;\">'
                                + '<option value=\"\">— Всички роли —</option>'
                                + '<option value=\"admin\">Администратор</option>'
                                + '<option value=\"user\">Потребител</option>'
                                + '<option value=\"student\">Ученик</option>'
                                + '<option value=\"teacher\">Учител</option>'
                                + '<option value=\"parent\">Родител</option>'
                                + '<option value=\"security\">Охрана</option>'
                                + '</select>';
                            $(this).closest('.dataTables_wrapper').find('.dataTables_length').append(roleSelect);
                        }",
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
            Column::make('name')->title('Име'),
            Column::make('Роля'),
            Column::make('email')->title('Имейл'),
            Column::make('Статус'),
            Column::make('Активност'),
            Column::make('Действие'),

        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'CustomerList_' . date('YmdHis');
    }
}
