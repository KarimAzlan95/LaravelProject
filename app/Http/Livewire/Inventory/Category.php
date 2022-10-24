<?php

namespace App\Http\Livewire\Inventory;

use App\Models\ProductCategories;
use Livewire\Component;
use Livewire\WithPagination;
use RealRashid\SweetAlert\Facades\Alert;

class Category extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $cat_name, $cat_code, $cat_status;
    public $modal_cat_name, $modal_cat_code, $modal_cat_status, $modal_cat_id;
    public $search = '';
    public $show_button = true;
    public $readonly = '';
    public $disabled = '';

    public function render()
    {
        return view('livewire.inventory.category', [
            'categories' => \App\Models\Category::where('category_status', true)
                ->where('category_code', 'like', '%' . $this->search . '%')
                ->orWhere('category_name', 'like', '%' . $this->search . '%')
                ->orderBy('id', 'DESC')
                ->paginate(5),
        ]);
    }

    public function resetInput()
    {
        $this->cat_code = '';
        $this->cat_name = '';
        $this->cat_status = '';

        $this->show_button = true;
        $this->readonly = '';
        $this->disabled = '';

        $this->modal_cat_status = '';
        $this->modal_cat_name = '';
        $this->modal_cat_code = '';

        $this->dispatchBrowserEvent('close_modal');
        $this->resetValidation();
        $this->resetPage();
    }

    public function confirmSimpan()
    {
        $this->validate([
            'cat_name' => 'required',
            'cat_code' => 'required',
            'cat_status' => 'required',
        ], [
            'cat_name.required' => 'Category Name is Required!',
            'cat_code.required' => 'Category Code is Required!',
            'cat_status.required' => 'Category Status is Required!',
        ]);

//        $this->dispatchBrowserEvent('test');

        $this->dispatchBrowserEvent('swal.category', [
            'type' => 'warning',
            'title' => 'Are you sure you want to continue?',
            'text' => '',
            'function' => 'SimpanCategory',
        ]);
    }

    public function SimpanCategory()
    {
        \App\Models\Category:: create([
            'category_name' => $this->cat_name,
            'category_code' => $this->cat_code,
            'category_status' => $this->cat_status,
        ]);

        $this->dispatchBrowserEvent('swal:modal_category', [
            'type' => 'success',
            'message' => 'Data Successfully Created!',
        ]);

        $this->emit('refresh_table_summary');
        $this->resetInput();
    }

    public function view_category($category_id)
    {
        $get_category = \App\Models\Category::where('id', $category_id)->first();
        $this->cat_status = $get_category->category_status;
        $this->cat_name = $get_category->category_name;
        $this->cat_code = $get_category->category_code;

        $this->show_button = false;
        $this->readonly = 'readonly';
        $this->disabled = 'disabled';
    }

    public function edit_category($category_id)
    {
        $get_details = \App\Models\Category::where('id', $category_id)->first();

        $this->modal_cat_id = $get_details->id;
        $this->modal_cat_code = $get_details->category_code;
        $this->modal_cat_name = $get_details->category_name;
        $this->modal_cat_status = $get_details->category_status;

        $this->dispatchBrowserEvent('open_modal');
    }

    public function delete_category($category_id)
    {
        $this->dispatchBrowserEvent('swal.category', [
            'type' => 'warning',
            'title' => 'Are you sure you want to continue?',
            'text' => '',
            'id' => $category_id,
            'function' => 'delete',
        ]);
    }

    public function delete($id)
    {
        ProductCategories::where('category_id', $id)->delete();

        $category_delete = \App\Models\Category::findOrFail($id);

        if ($category_delete) {

            \App\Models\Category::where('id', $id)->delete();
        }

        $this->emit('refresh_table_summary');
        $this->resetInput();
        $this->dispatchBrowserEvent('swal:modal_category', [
            'type' => 'success',
            'message' => 'Data Successfully Deleted!',
        ]);

    }

    public function update_category()
    {
        $this->validate([
            'modal_cat_code' => 'required',
            'modal_cat_name' => 'required',
            'modal_cat_status' => 'required',
        ], [
            'modal_cat_code.required' => 'Category Code is Required!',
            'modal_cat_name.required' => 'Category Name is Required!',
            'modal_cat_status.required' => 'Category Status is Required!',
        ]);

        $this->dispatchBrowserEvent('swal.category', [
            'type' => 'warning',
            'title' => 'Are you sure you want to continue?',
            'text' => '',
            'id' => $this->modal_cat_id,
            'function' => 'update_confirm',
        ]);
    }

    public function update_confirm($id)
    {
        $check = \App\Models\Category::findOrFail($id);
        if ($check) {
            $check->update([
                'category_code' => $this->modal_cat_code,
                'category_name' => $this->modal_cat_name,
                'category_status' => $this->modal_cat_status,
            ]);

            $this->emit('refresh_table_summary');
            $this->dispatchBrowserEvent('swal:modal_category', [
                'type' => 'success',
                'message' => 'Data Successfully Updated!',
            ]);
        } else {

            $this->dispatchBrowserEvent('swal:modal_category', [
                'type' => 'error',
                'message' => 'Error!',
            ]);

        }

        $this->resetInput();

    }

//    public function

    public function alertCancel()
    {
        $this->dispatchBrowserEvent('swal:modal_category', [
            'type' => 'success',
            'message' => 'Operation Cancelled!',
        ]);
    }
}
