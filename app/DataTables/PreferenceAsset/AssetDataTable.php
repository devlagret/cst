<?php

namespace App\DataTables\PreferenceAsset;

use App\Helpers\Configuration;
use App\Models\AcctInvoice;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class AssetDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('action', fn($model) =>view('content.PreferenceAsset.List._action-menu'))
            ->editColumn('client.client_id',fn($query)=>"{$query->client->name}")
            ->editColumn('product.type.product_type_id',fn($query)=>"{$query->product->type->name}")
            ->editColumn('product.product_id',fn($query)=>"{$query->product->name}")
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    // public function query(AcctInvoice $model): QueryBuilder
    // {
    //     return $model->newQuery()->with('client',"product.type");
    // }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('asset-menu')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->stateSave(true)
                    ->orderBy(0, 'asc')
                    ->dom('frtip')
                    ->responsive()
                    ->autoWidth(true)
                    ->addTableClass('align-middle table table-row-dashed fs-4 gy-4')
                    ;
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('asset_id')->title(__('No'))->data('DT_RowIndex') ->addClass('text-center')->style('width:5%;'),
            Column::make('name')->title("Nama")->style('width:12%;'),
            Column::make('buy_date')->title("Tanggal Beli")->style('width:12%;')->addClass('text-center'),
            Column::make('price')->title("Harga Beli")->width(20),
            Column::make('acquisition_amount')->title("Nilai Perolehan")->width(10),
            Column::make('estimated_age')->title("Taksiran Usia")->width(10),
            Column::make('residual_amount')->title("Nilai Residu")->width(10),
            Column::make('remark')->title("Keterangan")->width(10),
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(10)
                  ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Asset_' . date('YmdHis');
    }
}
