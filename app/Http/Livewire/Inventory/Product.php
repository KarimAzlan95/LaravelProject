<?php

namespace App\Http\Livewire\Inventory;

use App\Models\ProductCategories;
use App\Models\Products;
use Livewire\Component;
use Livewire\WithPagination;

class Product extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $product_name, $product_price, $search_product, $hidden_product_id;
    public $choose_product, $choose_category, $select_product, $pluck_product_id;
    public $get_categories, $table_row;
    public $button_filter_direction = 'A-Z';
    public $button_filter_price = 'low-high';
    public $text_button = 'change_order_1()';
    public $text_button_2 = 'low_to_high()';
    public $query = false;
    public $query2 = false;
    public $query3 = false;
    public $query4 = false;
    public $savebutton_text = 'SAVE';
    public $savebutton_click = 'confirmSave()';
    public $savebutton_style = 'btn btn-primary';
    public $readonly = '';
    public $search_table = '';
    public $show_button = true;
    public $show_category = false;
    public $array = [];

    public function render()
    {

        $this->select_products = Products::select('*')->get();
        $this->pluck_product_id = ProductCategories::distinct()
            ->select('products_id')
            ->get();

//        $this->summary_table = Products::with('categories')
//            ->whereIn('products.id', $this->pluck_product_id)
//            ->whereHas('categories', function ($q) {
//                $q->where('category_name', 'like', '%' . $this->search_table . '%');
//            })
//            ->orWhereHas('categories', function ($q) {
//                $q->where('category_code', 'like', '%' . $this->search_table . '%');
//            })
//            ->where('product_name', 'like', '%' . $this->search_table . '%')
//            ->orWhere('product_price', 'like', '%' . $this->search_table . '%')
//            ->paginate(5);


        return view('livewire.inventory.product', [
            'products' => Products::select('*')
                ->where('product_name', 'like', '%' . $this->search_product . '%')
                ->orWhere('product_price', 'like', '%' . $this->search_product . '%')
                ->when($this->query, fn($q) => $q->orderBy('product_name', 'ASC'))
                ->when($this->query2, fn($q) => $q->orderBy('product_name', 'DESC'))
                ->when($this->query3, fn($q) => $q->orderBy('product_price', 'ASC'))
                ->when($this->query4, fn($q) => $q->orderBy('product_price', 'DESC'))
                ->paginate(5),
            'summary_table' => Products::with('categories')
                ->whereIn('products.id', $this->pluck_product_id)
                ->WhereHas('categories', function ($q) {
                    $q->where('category_name', 'like', '%' . $this->search_table . '%');
                })
                ->orWhereHas('categories', function ($q) {
                    $q->where('category_code', 'like', '%' . $this->search_table . '%');
                })
                ->orWhere('product_name', 'like', '%' . $this->search_table . '%')
                ->orWhere('product_price', 'like', '%' . $this->search_table . '%')
                ->paginate(5),
        ]);
    }

    public function change_order_1()
    {
        $this->button_filter_direction = 'Z - A';
        $this->query = true;
        $this->text_button = 'change_order_2()';
    }

    public function change_order_2()
    {
        $this->button_filter_direction = 'RESET';
        $this->query = false;
        $this->query2 = true;
        $this->text_button = 'change_order_3()';
    }

    public function change_order_3()
    {
        $this->button_filter_direction = 'A - Z';
        $this->query2 = false;
        $this->text_button = 'change_order_1()';
    }

    public function low_to_high()
    {
        $this->button_filter_price = 'high - low';
        $this->query3 = true;
        $this->text_button_2 = 'high_to_low()';
    }

    public function high_to_low()
    {
        $this->button_filter_price = 'RESET';
        $this->query3 = false;
        $this->query4 = true;
        $this->text_button_2 = 'reset_price()';
    }

    public function reset_price()
    {
        $this->button_filter_price = 'low - high';
        $this->query4 = false;
        $this->text_button_2 = 'low_to_high()';
    }

    public function resetInputFields()
    {
        $this->product_name = '';
        $this->product_price = '';
        $this->hidden_product_id = '';

        $this->query = false;
        $this->query2 = false;
        $this->query3 = false;
        $this->query4 = false;

        $this->text_button = 'change_order_1()';
        $this->text_button_2 = 'low_to_high()';

        $this->button_filter_direction = 'A - Z';
        $this->button_filter_price = 'low - high';

        $this->show_button = true;
        $this->readonly = '';
        $this->savebutton_click = 'confirmSave()';
        $this->savebutton_text = 'SAVE';
        $this->savebutton_style = 'btn btn-primary';

        $this->choose_product = '';
        $this->array = [];
        $this->show_category = false;

        $this->resetValidation();
    }

    public function confirmSave()
    {
        $this->validate([
            'product_name' => 'required',
            'product_price' => 'required',
        ], [
            'product_name . required' => 'Product Name is Required!',
            'product_price . required' => 'Product Price is Required!',
        ]);

        $this->dispatchBrowserEvent('swal . confirm', [
            'type' => 'warning',
            'title' => 'Are you sure you want to continue?',
            'text' => '',
            'function' => 'Save',
        ]);
    }

    public function Save()
    {
        Products::create([
            'product_name' => $this->product_name,
            'product_price' => $this->product_price,
        ]);

        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'message' => 'Data Successfully Saved!',
        ]);

        $this->resetInputFields();
    }

    public function view_product($id)
    {
        $product = Products::where('id', $id)->first();

        $this->product_name = $product->product_name;
        $this->product_price = number_format($product->product_price, 2);

        $this->readonly = 'readonly';
        $this->show_button = false;
    }

    public function edit_product($id)
    {
        $edit_product = Products::where('id', $id)->first();

        $this->product_name = $edit_product->product_name;
        $this->product_price = number_format($edit_product->product_price, 2);
        $this->hidden_product_id = $id;

        $this->savebutton_text = 'UPDATE';
        $this->savebutton_click = 'confirmUpdate()';
        $this->savebutton_style = 'btn btn - success';
    }

    public function confirmUpdate()
    {
        $this->validate([
            'product_name' => 'required',
            'product_price' => 'required',
        ], [
            'product_name . required' => 'Product Name is Required!',
            'product_price . requried' => 'Product Price is Required!',
        ]);

        $this->dispatchBrowserEvent('swal.confirm', [
            'type' => 'warning',
            'title' => 'Are you sure you want to continue?',
            'text' => '',
            'id' => $this->hidden_product_id,
            'function' => 'Update',
        ]);
    }

    public function Update($product_id)
    {
        Products::where('id', $product_id)
            ->update([
                'product_name' => $this->product_name,
                'product_price' => $this->product_price,
            ]);

        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'message' => 'Data Successfully Updated!',
        ]);

        $this->resetInputFields();
    }

    public function delete_product($prod_id)
    {
        $this->dispatchBrowserEvent('swal.confirm', [
            'type' => 'warning',
            'title' => 'Are you sure you want to continue?',
            'text' => '',
            'id' => $prod_id,
            'function' => 'Delete',
        ]);
    }

    public function Delete($id)
    {
        $check = Products::findOrFail($id);

        if ($check) {
            $check->delete();

            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'message' => 'Data Successfully Deleted!',
            ]);
        } else {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'message' => 'An Error Has Occured, Please try Again!',
            ]);
        }
        return view('livewire . inventory . product');
    }

    public function get_categories()
    {
        $pluck = ProductCategories::where('products_id', $this->choose_product)
            ->select('category_id')
            ->get();

        $this->select_category = \App\Models\Category::whereNotIn('id', $pluck)
            ->where('category_status', true)
            ->get();

        $this->show_category = true;
    }

    public function confirmTag()
    {
        $this->validate([
            'choose_product' => 'required',
            'choose_category' => 'required',
        ], [
            'choose_product . required' => 'Choose Product from the Drop Down Above!',
            'choose_category . required' => 'Choose Category from the Drop Down Above!',
        ]);

        $this->dispatchBrowserEvent('swal.confirm', [
            'type' => 'warning',
            'title' => 'Are you sure you want to continue?',
            'text' => '',
            'function' => 'TagProduct',
        ]);
    }

    public function TagProduct()
    {
        ProductCategories::create([
            'products_id' => $this->choose_product,
            'category_id' => $this->choose_category,
        ]);

        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'message' => 'Product Successfully Added with Category!',
        ]);

        $this->resetInputFields();
    }

    public function alertCancel()
    {
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'message' => 'Operation Cancelled!',
        ]);
    }
}
