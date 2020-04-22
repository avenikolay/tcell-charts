<?php

namespace App\Http\Controllers;

use App\Imports\ProductsImport;
use App\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

//HeadingRowFormatter::default('none');

class FileController extends Controller
{
    public function upload(Request $request) {
        $request->validate([
           'report' => 'required|mimetypes:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ]);
        $fileName = uniqid().$request->file('report')->getFilename().'.xlsx';
        Storage::put($fileName, file_get_contents($request->file('report')->getRealPath()));

        $collection = Excel::toCollection(new ProductsImport, $fileName);
        $result = $collection[0]->map(function ($item, $key) {
            $date = $item['data_product'];
            return $item->map(function ($i, $k) use ($date) {
               if ($k !== 'data_product' && $k !== 'total') {
                   return [
                       'name' => $k,
                       'quantity' => $i,
                       'date' => $date,
                   ];
               }
            })->filter(function ($i, $k) {
                return $k !== 'data_product' && $k !== 'total';
            })->filter(function ($i) {
                return $i['date'] !== null && $i['quantity'] !== null;
            });
        })->flatten(1);

        Products::insertOrIgnore($result->toArray());
//        $result = Excel::toArray(new ProductsImport, Storage::get($fileName));
        return response()->json($result);
    }

    public function get() {
        return response()->json(Products::all());
    }
}
