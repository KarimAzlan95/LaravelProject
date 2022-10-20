<div>
    @include('livewire.inventory.edit_category')
    <div class="card">
        <div class="col-12">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 text-lg-center">
                        <b>Add New Category</b>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-6">
                        <div class="row mb-3">
                            <label for="cat_code" class="col-sm-4 col-form-label">Category Code</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="cat_code" name="cat_code"
                                       wire:model="cat_code" placeholder="--Place Category Code Here--" {{ $readonly }}>
                                @error('cat_code') <span
                                    class="text-danger font-weight-bold error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="cat_code" class="col-sm-4 col-form-label">Category Status</label>
                            <div class="col-sm-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="cat_status"
                                           id="ACTIVE" wire:model="cat_status" value="1" {{ $disabled }}>
                                    <label class="form-check-label" for="ACTIVE">
                                        ACTIVE
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="cat_status"
                                           id="INACTIVE" wire:model="cat_status" value="0" {{ $disabled }}>
                                    <label class="form-check-label" for="INACTIVE">
                                        INACTIVE
                                    </label>
                                </div>
                            </div>
                            @error('cat_status') <span
                                class="text-danger font-weight-bold error">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="row mb-3">
                            <label for="cat_name" class="col-sm-4 col-form-label">Category Name</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="cat_name" name="cat_name"
                                       wire:model="cat_name" placeholder="--Place Category Name Here--" {{ $readonly }}>
                                @error('cat_name') <span
                                    class="text-danger font-weight-bold error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent">
                <div class="col-md-12 text-sm-center">
                    <button type="button" class="btn btn-secondary" wire:click="resetInput()">
                        RESET
                    </button>
                    @if($show_button)
                        <button type="button" class="btn btn-primary" wire:click="confirmSimpan()">
                            ADD
                        </button>
                    @endif
                </div>
            </div>
            <div class="row">
                <center>
                    <div class="col-sm-10 text-sm-center">
                        <table class="table table-bordered table-hover table-responsive-sm text-nowrap">
                            <thead style="background-color: #C0C0C0;">
                            <tr>
                                <th>#</th>
                                <th>Category Code</th>
                                <th>Category Name</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i = 1; ?>
                            <div class="col-4">
                                <input type="text" class="form-control" id="search" name="search"
                                       wire:model="search" placeholder="--Search Details Here--">
                            </div>
                            @foreach($categories as $category)
                                @if($categories->count() > 0)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $category->category_code }}</td>
                                        <td>{{ $category->category_name }}</td>
                                        <td>
                                            @if($category->category_status)
                                                <span class="badge bg-success">ACTIVE</span>
                                            @else
                                                <span class="badge bg-danger">INACTIVE</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a type="button" wire:click="view_category('{{ $category->id }}')">
                                                <i class="fa-solid fa-eye" style="color: limegreen"></i>
                                            </a>
                                            <a type="button" wire:click="edit_category('{{ $category->id }}')">
                                                <i class="fa-solid fa-edit" style="color: blue;"></i>
                                            </a>
                                            <a type="button" wire:click="delete_category('{{ $category->id }}')">
                                                <i class="fa-solid fa-trash" style="color: red;"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td colspan="5" style="text-align: center">NO DATA</td>
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                        {!! $categories->links() !!}
                    </div>
                </center>
            </div>
        </div>
    </div>
</div>
{{--@push('js')--}}
<script>
    $(document).ready(function () {

        window.addEventListener('swal.confirm', event => {
            swal.fire({
                title: event.detail.title,
                text: event.detail.text,
                icon: event.detail.type,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                reverseButtons: true,
            }).then((result) => {
                if (result.value) {
                    @this.
                    call(event.detail.function, event.detail.id);
                } else {
                    @this.
                    call('alertCancel');
                }
            });
        });

        window.addEventListener('swal:modal', event => {
            Swal.fire({
                title: event.detail.message,
                text: event.detail.text,
                icon: event.detail.type,
            });
        });

        window.addEventListener('open_modal', event => {
            $('#edit_category_modal').modal({
                backdrop: 'static',
            });
        });

        window.addEventListener('close_modal', event => {
            $('#edit_category_modal').modal('hide');
        });

    });
</script>
{{--@endpush--}}
