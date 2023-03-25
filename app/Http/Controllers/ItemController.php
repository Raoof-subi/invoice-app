<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Dompdf\Dompdf;

class ItemController extends Controller
{
    public function create()
    {
        $Items = Item::all();
        return view('items', ['Items' => $Items]);
    }
    public function store(Request $request)
    {
        // $ValidatedData = $request->validate([
        //     'name' => ['required'],
        //     'quantity' => ['required'],
        //     'unit_price' => ['required'],
        //     'tax' => ['required']
        // ]);
        // (Price x Quantity) + (Tax x Price x Quantity)
        $inputdata = array(
            'name' => $request->name,
            'quantity' => $request->quantity,
            'unit_price' => $request->unit_price,
            'tax' => $request->tax,
            'total' => ($request->unit_price * $request->quantity) + (($request->tax / 100) * $request->unit_price * $request->quantity),
            'total_without_tax' => $request->quantity * $request->unit_price,
            'total_with_tax' => ($request->unit_price * $request->quantity) + (($request->tax / 100) * $request->unit_price * $request->quantity),
            // 'discount' => ($request->tax / 100) * ($request->quantity * $request->unit_price),
        );
        if ($request->discount_value != '') {
            if ($request->discount_type != 'flat') {
                $inputdata['total'] = $inputdata['total_with_tax'] - $request->discount_value;
                $inputdata['discount'] = $request->discount_value;
            } else if ($request->discount_type != 'Percentage') {
                $inputdata['total'] = $inputdata['total_with_tax'] - (($request->discount_value / 100) * $inputdata['total']);
                $inputdata['discount'] = (($request->discount_value / 100) * $inputdata['total']);
            }
        } else {
            $inputdata['discount'] = 0;
        }
        if (Item::create($inputdata)) {
            return redirect('/')->with('success', 'Item added');
        } else {
            return redirect('/')->with('error', 'somthing went wrong');
        }
    }

    public function exportPDF($id)
    {
        $data = Item::findOrFail($id);
        $pdf = new Dompdf();
        $html = '<h3>Invoice</h3><hr>';
        $html .= '<table>';
        $html .= '
                    <tr>
                        <th style="text-align:left; width:200px;">Name</th>
                        <th style="text-align:right; width:100px;">' . $data->name . '</th>
                    </tr>
                    <tr>
                        <th style="text-align:left; width:200px;">Quantity</th>
                        <th style="text-align:right; width:100px;">' . $data->quantity . '</th>
                    </tr>
                    <tr>
                        <th style="text-align:left; width:200px;">Unit price</th>
                        <th style="text-align:right; width:100px;">$ ' . $data->unit_price . '</th>
                    </r>
                    <tr>
                        <th style="text-align:left; width:200px;">Tax</th>
                        <th style="text-align:right; width:100px;">$ ' . $data->tax . '</th>
                    </tr>
                    <tr>
                        <th style="text-align:left; width:200px;">Discount</th>
                        <th style="text-align:right; width:100px;">' . $data->discount . '</th>
                    </tr>
                    <tr>
                        <th style="text-align:left; width:200px;">Total without tax</th>
                        <th style="text-align:right; width:100px;">$ ' . $data->total_without_tax . '</th>
                    </tr>
                    <tr>
                        <th style="text-align:left; width:200px;">Total with tax</th>
                        <th style="text-align:right; width:100px;">$ ' . $data->total_with_tax . '</th>
                    </tr>';

        $html .= '</table>';

        $html .= '<table>
        <tr>
            <th style="text-align:left; width:200px; font-size:20px;">Total</th>
            <th style="text-align:right; width:100px; font-size:20px;">$ ' . $data->total . '</th>
        </tr></table><hr>';


        $pdf->loadHtml($html);
        $pdf->setPaper('A4', 'portrait');
        $pdf->render();
        return $pdf->stream('export.pdf');
    }
}
