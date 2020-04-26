<?php

namespace App\Http\Controllers;

use App\Imports\ProductsImport;
use App\Product;
use Carbon\Carbon;
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
            $date = $item['activation_date'];
            return $item->map(function ($i, $k) use ($date) {
               if ($k !== 'activation_date') {
                   return [
                       'name' => $k,
                       'quantity' => ($i === null || $i === 0)  ? 0 : $i,
                       'date' => Carbon::parse($date)->format('Y-m-d')
                   ];
               }
            })->filter(function ($i) {
                return $i['date'] !== null && $i['quantity'] !== null;
            });
        })->flatten(1);

        Product::insertOrIgnore($result->toArray());
//        $result = Excel::toArray(new ProductsImport, Storage::get($fileName));
        return response()->json($result);
    }

    public function get(Request $request) {
        return response()->json(Product::orderBy('date', 'desc')->paginate(100));
    }

    public function getStats(Request $request) {
            $group = $request->query('group');
            $period = (((int) $request->query('period')) > 0) ? ((int) $request->query('period') - 1)  : 6;
            $lastDate = Product::max('date');
            $firstDate = Carbon::createFromFormat('Y-m-d', $lastDate)->subDays($period)->format('Y-m-d');
            switch ($group) {
                case 'darkor':
                    $data = Product::where('name', 'like', 'darkor%')
                        ->whereBetween('date', [$firstDate, $lastDate])
                        ->orderBy('date', 'asc')->get();
                    break;
                case 'alo':
                    $data = Product::where('name', 'like', 'alo%')
                        ->whereBetween('date', [$firstDate, $lastDate])
                        ->orderBy('date', 'asc')->get();
                    break;
                case 'socials':
                    $data = Product::whereBetween('date', [$firstDate, $lastDate])
                        ->where(function ($query) {
                            $query->where('name', 'like', 'messendzery')
                                ->orWhere('name', 'like', 'socialnye_seti')
                                ->orWhere('name', 'like', 'youtube');
                        })
                        ->orderBy('date', 'asc')->get();
                    break;
                default:
                    $data = [];
            }

        return response()->json($data);
    }
}
