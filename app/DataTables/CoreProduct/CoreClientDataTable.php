<?php

namespace App\DataTables\CoreProduct;

use App\Models\CoreClient;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class CoreClientDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return (new EloquentDataTable($query))
        ->addColumn('action', 'content.CoreProduct.ClientModal._action')
        ->addIndexColumn()
        ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\CoreClient $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(CoreClient $model)
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('branch-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->stateSave(true)
                    ->orderBy(0, 'asc')
                    ->dom('frtip')
                    ->responsive()
                    ->autoWidth(true)
                    // ->parameters(['scrollX' => true])
                    ->addTableClass('align-middle table table-row-dashed fs-4 gy-4')
                    ;
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::make('client_id')->title(__('No'))->data('DT_RowIndex') ->addClass('text-center')->width(10),
            Column::make('name')->title(__('Nama'))->width(50),
            Column::make('contact_person')->title(__('Contact Person'))->width(50),
            Column::make('phone')->title(__('No. Handphone'))->width(50),
            Column::computed('action')
            ->title(__('Aksi'))
            ->exportable(false)
            ->printable(false)
            ->width(10)
            ->addClass('text-center'),

        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename():string
    {
        return 'CoreBranch_' . date('YmdHis');
    }
}
