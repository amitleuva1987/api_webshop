<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_list_all_order_works()
    {
        $response = $this->json('get', 'api/orders');
        $response->assertStatus(200);
    }

    public function test_customer_can_create_an_order()
    {
        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseCount('customers', 0);
        $customer = Customer::create([
            'job_title' => 'Web Developer',
            'email' => 'Harvey_Thornton4640@hourpy.biz',
            'name' => 'Harvey Thornton',
            'registered_since' => '2019-03-09',
            'phone' => '1-781-821-4473'
        ]);

        $this->assertDatabaseCount('customers', 1);
        $response = $this->json('POST', 'api/orders', [
            'customer_id' => $customer->id,
            'paid' => 'no'
        ]);

        $orderid = $response->decodeResponseJson()['data']['id'];

        $response->assertStatus(200);
        $this->assertDatabaseCount('orders', 1);
    }

    public function test_single_order_can_be_viewed()
    {
        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseCount('customers', 0);
        $customer = Customer::create([
            'job_title' => 'Web Developer',
            'email' => 'Harvey_Thornton4640@hourpy.biz',
            'name' => 'Harvey Thornton',
            'registered_since' => '2019-03-09',
            'phone' => '1-781-821-4473'
        ]);

        $this->assertDatabaseCount('customers', 1);
        $response = $this->json('POST', 'api/orders', [
            'customer_id' => $customer->id,
            'paid' => 'no'
        ]);

        $response->assertStatus(200);
        $orderid = $response->decodeResponseJson()['data']['id'];
        $response1 = $this->json('GET', 'api/orders/' . $orderid);
        $response1->assertStatus(200);
        $this->assertDatabaseCount('orders', 1);
    }

    public function test_order_cab_be_updated()
    {
        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseCount('customers', 0);
        $customer = Customer::create([
            'job_title' => 'Web Developer',
            'email' => 'Harvey_Thornton4640@hourpy.biz',
            'name' => 'Harvey Thornton',
            'registered_since' => '2019-03-09',
            'phone' => '1-781-821-4473'
        ]);

        $this->assertDatabaseCount('customers', 1);
        $response = $this->json('POST', 'api/orders', [
            'customer_id' => $customer->id,
            'paid' => 'no'
        ]);

        $response->assertStatus(200);
        $orderid = $response->decodeResponseJson()['data']['id'];

        $this->json('PUT', 'api/orders/' . $orderid, [
            'customer_id' => $customer->id,
            'paid' => 'yes'
        ]);

        $this->assertDatabaseHas('orders', [
            'paid' => 'yes'
        ]);
    }

    public function test_order_delete_api_works()
    {
        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseCount('customers', 0);
        $customer = Customer::create([
            'job_title' => 'Web Developer',
            'email' => 'Harvey_Thornton4640@hourpy.biz',
            'name' => 'Harvey Thornton',
            'registered_since' => '2019-03-09',
            'phone' => '1-781-821-4473'
        ]);

        $this->assertDatabaseCount('customers', 1);
        $response = $this->json('POST', 'api/orders', [
            'customer_id' => $customer->id,
            'paid' => 'no'
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseCount('orders', 1);
        $orderid = $response->decodeResponseJson()['data']['id'];
        $response1 = $this->json('DELETE', 'api/orders/' . $orderid);
        $response1->assertStatus(200);
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_a_product_can_be_added_to_the_order()
    {
        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseCount('customers', 0);
        $customer = Customer::create([
            'job_title' => 'Web Developer',
            'email' => 'Harvey_Thornton4640@hourpy.biz',
            'name' => 'Harvey Thornton',
            'registered_since' => '2019-03-09',
            'phone' => '1-781-821-4473'
        ]);

        $this->assertDatabaseCount('customers', 1);
        $response = $this->json('POST', 'api/orders', [
            'customer_id' => $customer->id,
            'paid' => 'no'
        ]);
        $orderid = $response->decodeResponseJson()['data']['id'];
        $response->assertStatus(200);

        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseCount('products', 0);
        $product = Product::create([
            'product_name' => 'Marc Clothing',
            'price' => '73.38'
        ]);
        $this->assertDatabaseCount('products', 1);

        $response1 = $this->json('POST', 'api/orders/' . $orderid . '/add', [
            'product_id' => $product->id
        ]);
        $response1->assertStatus(200);
        $this->assertDatabaseCount('order_products', 1);
    }

    public function test_an_order_can_be_paid()
    {
        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseCount('customers', 0);
        $customer = Customer::create([
            'job_title' => 'Web Developer',
            'email' => 'Harvey_Thornton4640@hourpy.biz',
            'name' => 'Harvey Thornton',
            'registered_since' => '2019-03-09',
            'phone' => '1-781-821-4473'
        ]);

        $this->assertDatabaseCount('customers', 1);
        $response = $this->json('POST', 'api/orders', [
            'customer_id' => $customer->id,
            'paid' => 'no'
        ]);
        $orderid = $response->decodeResponseJson()['data']['id'];
        $response->assertStatus(200);

        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseCount('products', 0);
        $product = Product::create([
            'product_name' => 'Marc Clothing',
            'price' => '73.38'
        ]);
        $this->assertDatabaseCount('products', 1);

        $response1 = $this->json('POST', 'api/orders/' . $orderid . '/add', [
            'product_id' => $product->id
        ]);

        $response1->assertStatus(200);
        $this->assertDatabaseCount('order_products', 1);

        $response2 = $this->json('POST', 'api/orders/' . $orderid . '/pay');

        $response2->assertStatus(200);
    }
}
