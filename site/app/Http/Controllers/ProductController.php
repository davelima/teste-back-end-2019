<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Flugg\Responder\Responder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Responder $responder)
    {
        $products = Product::all();

        return $responder->success($products)->respond(200);
    }

    /**
     * @param Request $request
     * @param Responder $responder
     * @return JsonResponse
     */
    public function store(Request $request, Responder $responder)
    {
        $result = [
            'message' => 'Requisição inválida'
        ];

        $resultCode = 401;

        $postData = $request->only('name', 'price', 'weight');

        $rules = [
            'name' => 'required',
            'price' => 'required|numeric|between:0,9999999',
            'weight' => 'required|numeric'
        ];

        $validator = Validator::make($postData, $rules);

        if ($validator->validated()) {
            $product = new Product();
            $product->name = $postData['name'];
            $product->price = $postData['price'];
            $product->weight = $postData['weight'];
            $product->save();

            $result['message'] = 'Produto salvo com sucesso';
            $result['productId'] = $product->id;
            $resultCode = 201;

            return $responder->success($result)->respond($resultCode);
        }

        return $responder->error($resultCode, $result['message'])->respond($resultCode);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product, Responder $responder)
    {
        return $responder->success($product)->respond(200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param Responder $responder
     * @param  \App\Product  $product
     * @return JsonResponse
     */
    public function update(Request $request, Responder $responder, Product $product = null)
    {
        $result = [
            'message' => 'Requisição inválida'
        ];

        $resultCode = 401;

        $postData = $request->only('name', 'price', 'weight');

        $rules = [
            'price' => 'numeric|between:0,9999999',
            'weight' => 'numeric'
        ];

        $validator = Validator::make($postData, $rules);

        if ($validator->validated()) {
            foreach ($postData as $field => $value) {
                if ($value) {
                    $product->{$field} = $value;
                }
            }

            $product->save();

            $result['message'] = 'Produto salvo com sucesso';
            $result['productId'] = $product->id;
            $resultCode = 201;

            return $responder->success($result)->respond($resultCode);
        }

        return $responder->error($resultCode, $result['message'])->respond($resultCode);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product, Responder $responder)
    {
        $product->delete();

        return $responder->success(['Produto removido'])->respond(204);
    }
}
