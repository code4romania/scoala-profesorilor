<?php namespace Genuineq\Tms\Tests\Models;

use PluginTestCase;
use Carbon\Carbon;
use Genuineq\Tms\Models\Category;
use System\Classes\PluginManager;

class CategoryTest extends PluginTestCase
{
    public function test__createCategory()
    {
        /** Create new category */
        $category = $this->helper__createCategory();

        /* Validate created category */
        $this->assertEquals('test-category-name', $category->name);
        // $this->assertEquals('test-category-slug', $category->slug);
        $this->assertEquals('#FACFAC', $category->color);
        $this->assertEquals('test-category-icon', $category->icon);
        $this->assertEquals('test-category-description', $category->description);

        /** Delete category. */
        $category->delete();
    }

    public function test__updateCategory()
    {
        /** Create new category */
        $category = $this->helper__createCategory();

        /* Validate created category */
        $this->assertEquals('test-category-name', $category->name);
        // $this->assertEquals('test-category-slug', $category->slug);
        $this->assertEquals('#FACFAC', $category->color);
        $this->assertEquals('test-category-icon', $category->icon);
        $this->assertEquals('test-category-description', $category->description);

        /** Update category */
        $category->name = 'new-test-category-name';
        $category->slug = 'new-test-category-slug';
        $category->color = '#CAFCAF';
        $category->icon = 'new-test-category-icon';
        $category->description = 'new-test-category-description';
        $category->save();

        /** Check category new values */
        $this->assertEquals('new-test-category-name', $category->name);
        $this->assertEquals('new-test-category-slug', $category->slug);
        $this->assertEquals('#CAFCAF', $category->color);
        $this->assertEquals('new-test-category-icon', $category->icon);
        $this->assertEquals('new-test-category-description', $category->description);

        /** Delete category. */
        $category->delete();
    }

    public function test__deleteCategory()
    {
        /** Create new category */
        $category = $this->helper__createCategory();

        /* Validate created category */
        $this->assertEquals('test-category-name', $category->name);
        // $this->assertEquals('test-category-slug', $category->slug);
        $this->assertEquals('#FACFAC', $category->color);
        $this->assertEquals('test-category-icon', $category->icon);
        $this->assertEquals('test-category-description', $category->description);

        /** Save the ID */
        $categoryId = $category->id;

        /** Delete category. */
        $category->delete();

        /** Search for deleted category */
        $_category = Category::where('id', $categoryId)->first();

        $this->assertEquals(null, $_category);
    }


    /***********************************************
     ************** Helper Functions ***************
     ***********************************************/

    /**
     * Created an category object and return's it
     */
    protected function helper__createCategory()
    {
        /** Create new category */
        $category = Category::create([
            'name' => 'test-category-name',
            'slug' => 'test-category-slug' . '-' . Carbon::now()->timestamp,
            'color' => '#FACFAC',
            'icon' => 'test-category-icon',
            'description' => 'test-category-description',
        ]);

        return $category;
    }
}
