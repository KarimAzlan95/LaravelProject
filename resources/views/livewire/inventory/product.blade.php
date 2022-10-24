<div>
    <div class="card">
        <div class="col-12">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 text-lg-center">
                        <h4>Add New Product</h4>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-6">
                        <div class="row mb-4">
                            <div class="col-sm-4">
                                <label for="product_name" class="col-form-label">Product Name</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="product_name" id="product_name"
                                       placeholder="--Enter Product Name--" wire:model="product_name" {{ $readonly }}>
                                <input type="hidden" name="hidden_product_id" wire:model="hidden_product_id">
                            </div>
                            @error('product_name') <span
                                class="text-danger font-weight-bold error">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="row mb-4">
                            <div class="col-sm-4">
                                <label for="product_price" class="col-form-label">Product Price(RM)</label>
                            </div>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="product_price" id="product_price"
                                       placeholder="--Enter Product Price--" wire:model="product_price" {{ $readonly }}>
                            </div>
                            @error('product_price') <span
                                class="text-danger font-weight-bold error">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent">
                <div class="col-md-12 text-sm-center">
                    <button type="button" class="btn btn-secondary" wire:click="resetInputFields()">
                        RESET
                    </button>
                    @if($show_button)
                        <button type="button" class="{{ $savebutton_style }}" wire:click="{{ $savebutton_click }}">
                            {{ $savebutton_text }}
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
                                <th>Product Name</th>
                                <th>Product Price(RM)</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <div class="float-start">
                                <input type="text" class="form-control col-sm-4" id="search_product"
                                       name="search_product"
                                       wire:model="search_product" placeholder="--Search Product Here--">
                            </div>
                            <div class="float-end">
                                <button class="btn btn-outline-info btn-sm" type="button"
                                        wire:click="{{ $text_button }}">
                                    {{ $button_filter_direction }}
                                </button>
                                <button class="btn btn-outline-info btn-sm" type="button"
                                        wire:click="{{ $text_button_2 }}">
                                    {{ $button_filter_price }}
                                </button>
                            </div>
                            <?php $i = 1; ?>
                            @foreach($products as $data)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $data->product_name }}</td>
                                    <td>{{ number_format($data->product_price, 2) }}</td>
                                    <td>
                                        <a type="button" wire:click="view_product('{{ $data->id }}')">
                                            <i class="fa-solid fa-eye" style="color: limegreen"></i>
                                        </a>
                                        <a type="button" wire:click="edit_product('{{ $data->id }}')">
                                            <i class="fa-solid fa-edit" style="color: blue;"></i>
                                        </a>
                                        <a type="button" wire:click="delete_product('{{ $data->id }}')">
                                            <i class="fa-solid fa-trash" style="color: red;"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {!! $products->links() !!}
                    </div>
                </center>
            </div>
            <hr>
            <div class="row">
                <div class="col-12 text-lg-center">
                    <h4>Tag Product to Categories</h4>
                </div>
            </div>
            <br>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-2">
                        <label for="product_name" class="col-form-label">
                            Choose Product :
                        </label>
                    </div>
                    <div class="col-sm-3">
                        <select id="choose_product" name="choose_product"
                                wire:model.lazy.defer="choose_product" wire:change="get_categories()"
                                class="form-control">
                            <option value="">--Select Product Here--</option>
                            @foreach($select_products as $data)
                                <option value="{{ $data->id }}">{{ $data->product_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if($show_category)
                        <div class="col-sm-2">
                            Choose Category :
                        </div>
                        <div class="col-sm-3">
                            <select id="choose_category" name="choose_category" class="form-control"
                                    wire:model.lazy.defer="choose_category">
                                <option value="">--Choose a Category Here--</option>
                                @foreach($select_category as $sc)
                                    <option
                                        value="{{ $sc->id }}">{{ $sc->category_code.'-'.$sc->category_name }}</option>
                                @endforeach
                            </select>
                            @error('choose_category') <span
                                class="text-danger font-weight-bold error">{{ $message }}</span>@enderror
                        </div>
                    @endif
                </div>
                <br>
                <br>
                <div class="bg-transparent">
                    <div class="col-md-12 text-sm-center">
                        <button type="button" class="btn btn-secondary" wire:click="resetInputFields()">
                            RESET
                        </button>
                        <button type="button" class="btn btn-primary" wire:click="confirmTag()">
                            ADD
                        </button>
                    </div>
                    <div class="text-sm-center">
                        @error('choose_product') <span
                            class="text-danger font-weight-bold error">{{ $message }}</span>@enderror
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="text-lg-center">
                        <h4>Table Summary</h4>
                    </div>
                    <div class="col-sm-1"></div>
                    <div class="col-sm-10" style="text-align: center;">
                        <div class="float-start">
                            <input type="text" class="form-control" placeholder="--Search Your Products Here--"
                                   wire:model="search_table" name="search_table" id="search_table">
                        </div>
                        <table class="table table-bordered table-hover table-responsive-sm text-nowrap">
                            <thead style="background-color: #C0C0C0;">
                            <tr>
                                <td>#</td>
                                <td>Product Name</td>
                                <td>Product Price</td>
                                <td>Category</td>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $k = 1; ?>
                            @foreach($summary_table as $product)
                                <tr>
                                    <td>{{ $k++ }}</td>
                                    <td>{{ $product->product_name }}</td>
                                    <td>{{ number_format($product->product_price, 2) }}</td>
                                    <td>
                                        <ul>
                                            @foreach($product->categories as $category)
                                                <li>{{ $category->category_code .'-'. $category->category_name }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {!! $summary_table->links() !!}
                    </div>
                    <div class="col-sm-1"></div>
                </div>
            </div>
        </div>
    </div>
</div>
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
    });
</script>
