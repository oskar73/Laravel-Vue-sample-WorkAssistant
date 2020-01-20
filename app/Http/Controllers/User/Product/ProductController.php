<?php

namespace App\Http\Controllers\User\Product;

use App\Http\Controllers\User\UserController;
use App\Models\Builder\Section;
use App\Models\Builder\SectionCategory;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductColor;
use App\Models\ProductSize;
use App\Models\ProductUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends UserController
{
    public $weekday;

    public function __construct(Product $model)
    {
        $this->model = $model;
        $this->sortModel = $this->model;
    }
    public function index()
    {
        if (request()->wantsJson()) {
            try {
                $products = $this->model->my()->orderBy("order")
                    ->get();
                $selector = "all";
                $data = view('components.user.products', compact("products", "selector"))->render();

                return response()->json([
                    'status' => 1,
                    'data' => $data,
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 0,
                    'data' => [json_encode($e->getMessage())],
                ]);
            }
        }

        return view(self::$viewDir.'product.index');
    }
    public function create()
    {
        $data['categories'] = ProductCategory::active()->get();
        $data['units'] = ProductUnit::all();
        $data['sizes'] = ProductSize::all();
        $data['colors'] = ProductColor::all();

        return view(self::$viewDir.'product.productCreate', $data);
    }
    public function store(Request $request)
    {
        try {
            $rule = $this->model->storeRule($request);

            $validation = Validator::make($request->all(), $rule, $this->model::CUSTOM_VALIDATION_MESSAGE);
            if ($validation->fails()) {
                return response()->json([
                    'status' => 0,
                    'data' => $validation->errors(),
                    'yoo' => 1,
                ]);
            }

            $item = $this->model->storeItem($request);

            return response()->json([
                'status' => 1,
                'data' => 1,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
                'yoo' => 3,
            ]);
        }
    }
    public function edit($id)
    {
        $data['product'] = $this->model->findorfail($id);
        $data['categories'] = ProductCategory::active()->get();
        $data['units'] = ProductUnit::all();
        $data['sizes'] = ProductSize::all();
        $data['colors'] = ProductColor::all();

        return view(self::$viewDir.'product.productEdit', $data);
    }
    public function update(Request $request, $id)
    {
        try {
            $rule = $this->model->storeRule($request);

            $validation = Validator::make($request->all(), $rule, $this->model::CUSTOM_VALIDATION_MESSAGE);
            if ($validation->fails()) {
                return response()->json([
                    'status' => 0,
                    'data' => $validation->errors(),
                    'yoo' => 1,
                ]);
            }

            $item = $this->model->findOrFail($id)->storeItem($request);

            return response()->json([
                'status' => 1,
                'data' => 1,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
                'yoo' => 3,
            ]);
        }
    }
    public function switchListing(Request $request)
    {
        try {
            $action = $request->action;
            $products = $this->model->whereIn('id', $request->ids)->get();

            if ($action === 'active') {
                $products->each->update(['status' => true]);
            } elseif ($action === 'inactive') {
                $products->each->update(['status' => false]);
            } elseif ($action === 'delete') {
                $products->each->delete();
            }

            return response()->json(['status' => 1]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'data' => [json_encode($e->getMessage())],
            ]);
        }
    }
    public function forBuilder()
    {
        try {
            $catProductsId = SectionCategory::where("slug", "products")->first()->id;
            $productSections = Section::where("category_id", 25)->get();

            $productNewSections = [];
            $dummyItems = [];

            for ($i = 1; $i <= 3; $i++) {
                $tempItems = (object) [
                    "title" => "Product ".$i,
                    "description" => "Product description",
                    "subtitle" => "Type your text",
                    "button" => (object) [
                        "title" => "Add to cart",
                    ],
                    "image" => (object) [
                        "src" => "/images/sample/5.jpg",
                    ],
                ];
                array_push($dummyItems, $tempItems);
            }

            $products = $this->model->my()
                ->with('category:id,name', 'unit:id,name', 'additionalPrices')
                ->active()->orderBy("order")
                ->get();

            $productArr = [];

            foreach ($products as $product) {
                $additionalPrices = $product->additionalPrices()->with('additionals')->get();
                $sizes = [];
                $colors = [];

                foreach ($additionalPrices as $additionalPrice) {
                    if (str_contains($additionalPrice->additionals->getMorphClass(), 'ProductSize')) {
                        $sizes[] = [
                            'name' => $additionalPrice->additionals->name,
                            'price' => $additionalPrice->price,
                        ];
                    } elseif (str_contains($additionalPrice->additionals->getMorphClass(), 'ProductColor')) {
                        $colors[] = [
                            'name' => $additionalPrice->additionals->name,
                            'price' => $additionalPrice->price,
                        ];
                    }
                }

                $productArr[] = (object) [
                    'id' => $product->id,
                    'title' => $product->name,
                    'description' => $product->description ?? '...',
                    'image' => (object) [
                        'src' => $product->getFirstMediaUrl('thumbnail'),
                    ],
                    'price' => $product->price,
                    'sizes' => implode(', ', array_column($sizes, 'name')),
                    'colors' => implode(', ', array_column($colors, 'name')),
                    'button' => (object) [
                        'title' => "Add to cart",
                    ],
                ];
            }

            foreach ($productSections as $key => $section) {
                $tempSection = (object) [
                    "id" => $section->id,
                    "category_id" => $catProductsId,
                    "name" => 'Product'.($key + 1),
                    "status" => 1,
                    "order" => null,
                    "data" => (object) [
                        "background" => (object) [],
                        "setting" => (object) [
                            'layout' => 1,
                            'columns' => [2, 3, 4],
                            'column' => 3,
                            'paypal' => (object) [
                                'paypalAccount' => '',
                                'currency' => '$',
                                'cartButton' => true,
                            ],
                            'layouts' => (object) [
                                'sectionSize' => '1',
                                'shadow' => true,
                                'aspectRatio' => 'square',
                                'aspectRatios' => ["circle", "square", "landscape", "portrait", "original"],
                                'animation' => 'bouncy',
                                'interval' => 2,
                                'fullSize' => false,
                                'alignment' => "center",
                                'carousel' => true,
                            ],
                            'elements' => (object) [
                                "description" => false,
                                "title" => true,
                                "subtitle" => true,
                                "navigation" => true,
                                "loop" => true,
                                "autoPlay" => true,
                            ],
                            "listElements" => (object) [
                                "title" => true,
                                "description" => true,
                                "buttons" => true,
                                "price" => true,
                                "sizes" => true,
                                "colors" => true,
                                "image" => true,
                            ],
                        ],
                        "data" => (object) [
                            'elements' => (object) [
                                "title" => "Product",
                                "subtitle" => "CLick here to edit your subtitle",
                                "description" => "Add a description here",
                                "button" => (object) [
                                    "title" => "View Cart",
                                    ],
                            ],
                            "items" => $productArr,
                        ],
                    ],
                ];

                array_push($productNewSections, $tempSection);
            }

            return response()->json([
                'status' => 1,
                'items' => $productNewSections,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0,
                'message' => [json_encode($e->getMessage())],
            ]);
        }
    }
}
