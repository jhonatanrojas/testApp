<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Goutte\Client;
use  App\models\Product;
use  App\models\Item;
class scrapingProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public $image = [];
    public $linkProduct = [];
    public $title = [];
    public $description = [];
    public $price = [];
    public $prducts = [];
    public function index()
    {


        $client = new Client();
        $crawler =    $client->request('GET', 'https://www.appliancesdelivered.ie/search/small-appliances?sort=price_desc');
        //


        $all_links = [];
        $crawler->filter('.search-results .search-results-product')->each(function ($node, $i) {

            $divs =  $node->children()->filter('div');
            $image = $divs->eq(0);

            $titles = $divs->eq(2);
            //get the title product
         $title = $titles->filter('div > h4');
         $this->title[]=$title->text();

            //get the price product
            $price = $titles->filter('div > h3');
            $this->price[] =$price->text();


            $description = [];
            //get the description  product
            $results = $titles->filter('ul li')->each(function ($nodei, $i) {


                $description[] = $nodei->text();

                return  $description;
            });


            $this->description[] = $results;
            $links = $image->filter('a')->links();
            $link2 = $image->filter('picture source');
            //get  product link
            $attr = $link2->attr('data-srcset');
            $this->image[] = $attr;

            //get the image  link
            foreach ($links as $link) {
                $this->linkProduct[] = $link->getURI();
                $all_links[] = $link->getURI();
            }


            //   $nameNode= $node->filter("[class='product-image']")->first();

            //print $nameNode->text();
        });

        //link all

       

        foreach (   $this->title as $key => $value) {

    

            $data=array("title"=>$value,
                        "linkProduct"=> $this->linkProduct[$key],
                        "image"=> $this->image[$key],
                        "price"=> $this->price[$key],
                    );

        $flight =Product::where('title', $value)->first();
          
        if(!$flight){
          $result= Product::create($data);
     
          

        foreach ($this->description[$key] as $key => $values) {
            $data=array("description"=>$values[0],
                        "product_id"=>$result->id);
                      
                        Item::create($data);
        }
    }
          /*  $this->prducts[] = array(
                "title" => $value,
                "linkProduct" =>  $this->linkProduct[$key],
                "image" =>  $this->image[$key],
                "price" =>  $this->price[$key],
                "description"=> $this->description[$key]
            );*/
        }
        echo "true";
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
