<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Frontend;
use App\Models\Level;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function __construct()
    {
        $this->activeTemplate = activeTemplate();
    }

    public function products(Request $request)
    {
        $products     = Product::available();
        $routeProduct = $this->routeByProduct($products);
        $pageTitle    = $routeProduct['pageTitle'];
        $products     = $routeProduct['products'];
        $this->searchAndTagProduct($products);

        $cloneProducts = clone $products;
        $minPrice      = $cloneProducts->min('regular_price') ?? 0;
        $maxPrice      = $cloneProducts->max('regular_price') ?? 0;

        $products     = $products->with('category', 'user')->latest()->paginate(getPaginate());
        $totalProduct = $cloneProducts->count();
        $tags         = $this->getTags($cloneProducts->pluck('tag'));
        $allCategory  = Category::active()->select(['id', 'name'])->get();
        return view($this->activeTemplate . 'product.all', compact('pageTitle', 'products', 'minPrice', 'maxPrice', 'tags', 'totalProduct', 'allCategory'));
    }

    public function categoryProducts(Request $request, $id)
    {

        $products      = Product::available();
        $categoryId    = 0;
        $subcategoryId = 0;

        if ($request->route()->getName() == 'category.products' || $request->route()->getName() == 'category.featured.products' || $request->route()->getName() == 'category.weekly.best.products' || $request->route()->getName() == 'category.best.selling.products') {
            $categoryId    = $id;
            $category      = Category::active()->with('subcategories')->find($id);
            $subcategories = $category->subcategories;
            $pageTitle     = $category->name . '- Products';

            $products = $products->where('category_id', $categoryId);

            if ($request->route()->getName() == 'category.featured.products') {
                $products = $products->featured();
            } else if ($request->route()->getName() == 'category.weekly.best.products') {
                $date     = weeklyDates();
                $products = $products
                    ->whereBetween('created_at', [$date[0], $date[1]])
                    ->where('total_sell', '>', 0)
                    ->orderBy('total_sell', 'desc');
            } else if ($request->route()->getName() == 'category.best.selling.products') {
                $products = $products->orderBy('total_sell', 'desc');
            }
        }

        if ($request->route()->getName() == 'subcategory.products') {
            $subcategoryId = $id;
            $subcategory   = Subcategory::active()->find($id);
            $pageTitle     = $subcategory->name . '- Products';

            $products      = $products->where('subcategory_id', $subcategoryId);
            $subcategories = null;
        }

        $cloneProducts = clone $products;

        $minPrice     = $cloneProducts->min('regular_price') ?? 0;
        $maxPrice     = $cloneProducts->max('regular_price') ?? 0;
        $products     = $products->with('category', 'user')->latest()->paginate(getPaginate());
        $totalProduct = $cloneProducts->count();
        $tags         = $this->getTags($cloneProducts->pluck('tag'));
        return view($this->activeTemplate . 'product.all', compact('pageTitle', 'products', 'subcategories', 'minPrice', 'maxPrice', 'tags', 'categoryId', 'subcategoryId', 'totalProduct'));
    }

    public function productsFilter(Request $request)
    {
        $products     = Product::available();
        $routeProduct = $this->routeByProduct($products);
        $this->searchAndTagProduct($products);
        $this->getByCategory($products);
        $this->productsQuery($products, $request);

        $totalProduct = $products->count();

        $products = $products->with('category', 'user')->latest()->paginate(getPaginate());

        $data = [
            'view' => view($this->activeTemplate . 'product.card.list', compact('products'))->render(),
            'totalProduct' => $totalProduct,
        ];

        return response()->json($data);
    }

    public function routeByProduct($products)
    {
        $request   = request();
        $pageTitle = 'All Products';

        if ($request->route()->getName() == 'featured.products' || $request->route == 'featured.products' || $request->route == 'category.featured.products') {
            $pageTitle = 'Featured Products';
            $products  = $products->featured();
        }

        if ($request->route()->getName() == 'best.selling.products' || $request->route == 'best.selling.products' || $request->route == 'category.best.selling.products') {
            $pageTitle = 'Best Selling Products';
            $products  = $products->where('total_sell', '>', 0)->orderBy('total_sell', 'desc');
        }

        if ($request->route()->getName() == 'best.author.products' || $request->route == 'best.author.products') {
            $pageTitle = 'Best Author Products';
            $products  = $products->selectRaw('products.*, (avg_rating*total_sell) as point')->orderBy('point', 'desc');
        }

        if ($request->route()->getName() == 'weekly.best.products' || $request->route == 'weekly.best.products' || $request->route == 'category.weekly.best.products') {
            $pageTitle = 'Weekly Best Products';
            $date      = weeklyDates();
            $products  = $products
                ->whereBetween('created_at', [$date[0], $date[1]])
                ->where('total_sell', '>', 0)
                ->orderBy('total_sell', 'desc');
        }

        return $data[] = [
            'products'  => $products,
            'pageTitle' => $pageTitle,
        ];
    }

    public function searchAndTagProduct($products)
    {
        $request = request();
        if ($request->search) {
            $products = $products->searchable(['name', 'description', 'category:name', 'subcategory:name']);
        }

        if ($request->tags) {
            foreach ($request->tags as $tag) {
                $product = Product::available()->whereJsonContains('tag', $tag)->select('id')->first();
                $tagId[] = $product->id;
            }
            $products = $products->whereIn('id', $tagId);
        }
        return $products;
    }

    public function getByCategory($products)
    {
        $request = request();
        if ($request->categoryId && $request->categoryId != 0) {
            $products = $products->where('category_id', $request->categoryId);
            $products = $this->subcategoriesQuery($products, $request);
        } else {
            $products = $this->categoriesQuery($products, $request);
        }

        if ($request->subcategoryId) {
            $products = $products->where('subcategory_id', $request->subcategoryId);
        }
        return $products;
    }

    protected function getTags($tagsArray)
    {
        $tags = [];

        foreach ($tagsArray as $value) {
            $tags = array_merge($value, $tags);
        }

        $tags = array_unique($tags);
        return $tags;
    }

    protected function categoriesQuery($productList, $request)
    {

        if ($request->categories) {
            $productList = $productList->whereIn('category_id', $request->categories);
        }
        return $productList;
    }

    protected function subcategoriesQuery($productList, $request)
    {

        if ($request->subcategories) {
            $productList = $productList->whereIn('subcategory_id', $request->subcategories);
        }

        return $productList;
    }

    protected function productsQuery($productFilter, $request)
    {

        if ($request->min && $request->max) {
            $productFilter = $productFilter->whereBetween('regular_price', [$request->min, $request->max]);
        }

        if ($request->sort) {
            $sort       = explode('_', $request->sort);
            $columnName = $sort[0];

            if ($sort[0] == 'price') {
                $columnName = 'regular_price';
            }

            if ($sort[0] == 'totalSell' || $sort[0] == 'totalReview') {
                $explodeColumn = substr($sort[0], 5);
                $columnName    = 'total' . '_' . strtolower($explodeColumn);
            }
            $productFilter = $productFilter->orderBy(@$columnName, @$sort[1]);
        }

        if ($request->rating) {
            $productFilter = $productFilter->where('avg_rating', '>=', $request->rating);
        }

        return $productFilter;
    }

    public function detail($id, $slug)
    {
        $pageTitle       = 'Product Detail';
        $product         = Product::available()->with(['category', 'user', 'user.products', 'reviews.user'])->withCount('reviews')->findOrFail($id);
        $moreProducts    = Product::available()->where('user_id', $product->user_id)->where('id', '!=', $id)->with(['category', 'user', 'reviews'])->limit(6)->inRandomOrder()->get();
        $reviews         = $product->reviews()->with('user')->paginate(getPaginate());
        $comments        = $product->comments()->with(['replies', 'user', 'replies.user', 'product'])->paginate(getPaginate());
        $levels          = Level::get();
        $user            = auth()->user();
        $buyAuthenticate = [];
        if ($user) {
            $buyAuthenticate = $user->mySell()->where('product_id', $id)->pluck('license')->toArray();
        }
        $customPageTitle                   = $product->name;
        $seoContents['keywords']           = $product->meta_keywords ?? [];
        $seoContents['social_title']       = $product->name;
        $seoContents['description']        = strLimit(strip_tags($product->description), 150);
        $seoContents['social_description'] = strLimit(strip_tags($product->description), 150);
        $seoContents['image']              = getImage(getFilePath('product') . '/' . $product->featured_image, getFileSize('product'));
        $seoContents['image_size']         = getFileSize('product');

        return view($this->activeTemplate . 'product.detail', compact('pageTitle', 'product', 'moreProducts', 'levels', 'reviews', 'comments', 'seoContents', 'customPageTitle', 'buyAuthenticate'));
    }

    public function productSupport()
    {
        $policy    = Frontend::where('data_keys', 'support.content')->first();
        $pageTitle = 'Support Details';
        return view($this->activeTemplate . 'policy', compact('pageTitle', 'policy'));
    }
}
