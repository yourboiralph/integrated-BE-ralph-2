<?php

namespace App\Repository;

use App\Interface\Repository\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;

class ProductRepository implements ProductRepositoryInterface
{
    public function getAll()
    {
        return Product::paginate(20);
    }

    public function getById(int $id)
    {
        return Product::findOrFail($id);
    }

    public function create(object $data)
{
    $product = new Product();
    $product->name = $data->name;
    $product->description = $data->description;
    $product->price = $data->price;
    $product->quantity = $data->quantity;

    // Check if a photo is provided and it's an instance of UploadedFile
    if (isset($data->photo) && $data->photo instanceof UploadedFile) {
        // Generate a unique file name
        $fileName = uniqid() . '.' . $data->photo->getClientOriginalExtension();
        
        // Move the uploaded file to public/products folder
        $data->photo->move(public_path('products'), $fileName);
        
        // Save the file path in the database
        $product->photo = 'products/' . $fileName;
    }

    $product->save();

    return $product->fresh();
}

    public function update(object $data, int $id)
    {
        $product = Product::findOrFail($id);
        $product->name = $data->name;
        $product->description = $data->description;
        $product->price = $data->price;
        $product->quantity = $data->quantity;
        $product->save();

        return $product->fresh();
    }

}
