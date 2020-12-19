<?php


namespace Feature\Http\Controllers\Foodfleet\Documents\Template;

use Illuminate\Support\Facades\Artisan;
use InvalidArgumentException;
use Tests\TestCase;

class TypesTest extends TestCase
{
    /**
     * @test
     */
    public function testDocumentTemplateTypesNotExistingDoesNotFailRouteList()
    {
        //Given

        //the class may not exist,
        $class = "\\App\\Http\\Controllers\\Foodfleet\\Documents\\Template\\Types";

        try {
            $obj = new $class();
        } catch (\Exception|\Throwable $e) {
            if ($e->getMessage() == "Class '\App\Http\Controllers\Foodfleet\Documents\Template\Types' not found") {
                //can call the route:list without errors
                Artisan::call("route:list");

                //assert no errors occur
                $output = Artisan::output();

                $this->assertEmpty(trim($output));

                //also assert that the route is not found
                $this->expectException(InvalidArgumentException::class);

                $route = route('document/template/types');
                //above should trigger the invalid_argument_exception if the route is defined.
            }
        }
    }
}
