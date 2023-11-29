<?php

namespace App\DataTables;

use App\Models\ProductType;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ProductTypeDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable($query)
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('action', 'content.ProductType.List._action-menu')
            ->editColumn('account.account_code',fn($query)=>"{$query->account->account_code} - {$query->account->account_name}")
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(ProductType $model): QueryBuilder
    {
        return $model->newQuery()->with('account');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('producttype-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->stateSave(true)
                    ->orderBy(0, 'asc')
                    ->dom('frtip')
                    ->responsive()
                    ->autoWidth(false)
                    ->parameters(['scrollX' => true])
                    ->addTableClass('align-middle table table-row-dashed fs-4 gy-4');
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('product_type_id')->title(__('No'))->data('DT_RowIndex') ->addClass('text-center')->width(10),
            Column::make('name')->title(__('Nama'))->width(70),
            Column::make('code')->title(__('Kode'))->width(20),
            Column::make('account.account_code')->title(__('No.Perkiraan'))->width(50),
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(50)
                  ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'ProductType_' . date('YmdHis');
    }
}
