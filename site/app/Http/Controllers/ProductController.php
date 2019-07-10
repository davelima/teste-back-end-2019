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
     * @param Responder $responder
     * @return JsonResponse
     */
    public function index(Responder $responder): JsonResponse
    {
        $products = Product::all();

        return $responder->success($products)->respond(200);
    }

    /**
     * @param Request $request
     * @param Responder $responder
     * @return JsonResponse
     */
    public function store(Request $request, Responder $responder): JsonResponse
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
     * @param Responder $responder
     * @return JsonResponse
     */
    public function show(Product $product, Responder $responder): JsonResponse
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
    public function update(Request $request, Responder $responder, Product $product): JsonResponse
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
            $resultCode = 200;

            return $responder->success($result)->respond($resultCode);
        }

        return $responder->error($resultCode, $result['message'])->respond($resultCode);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Product $product
     * @param Responder $responder
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Product $product, Responder $responder): JsonResponse
    {
        $product->delete();

        return $responder->success(['Produto removido'])->respond(204);
    }
}
