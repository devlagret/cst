
<td class="text-center">
    <a type="button" href="{{route('client.detail',$model->client_id)}}" class="btn btn-sm btn-info btn-active-light-info">
        Detail
    </a>
    <a type="button" href="{{route('client.edit',$model->client_id)}}" class="btn btn-sm btn-warning btn-active-light-warning">
        Edit
    </a>
    <button type="button" class="btn btn-sm btn-danger btn-active-light-danger" data-bs-toggle="modal" data-bs-target="#kt_modal_delete_{{$model->client_id}}">
        Hapus
      </button>
</td>

<div class="modal fade" tabindex="-1" id="kt_modal_delete_{{$model->client_id}}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Hapus Client</h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <span class="bi bi-x-lg"></span>
                </div>
            </div>
            <div class="modal-body">
                <p>Apakah anda yakin ingin menghapus Client?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">Tidak</button>
                <a href="{{route('client.delete',$model->client_id)}}" class="btn btn-danger">Iya</a>
            </div>
        </div>
    </div>
</div>