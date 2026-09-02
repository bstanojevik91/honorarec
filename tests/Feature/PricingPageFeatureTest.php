<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PricingPageFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_pricing_page_is_public_and_displays_all_expected_package_content(): void
    {
        $response = $this->get('/cenovnik');

        $response->assertOk()
            ->assertSee('Ценовни пакети за работодавачи')
            ->assertSee('Basic')
            ->assertSee('2.590 ден.')
            ->assertSee('Honorarec+')
            ->assertSee('5.990 ден.')
            ->assertSee('Honorarec Partner')
            ->assertSee('15.990 ден.')
            ->assertSee('НАЈПОПУЛАРЕН')
            ->assertSee('PREMIUM')
            ->assertSee('tel:+38970214325', false)
            ->assertSee('Јавете се: 070 214 325')
            ->assertSee('Пакетите во моментов се активираат по директен контакт.')
            ->assertSee('href="#pricing-contact"', false);
    }

    public function test_pricing_page_stays_out_of_homepage_navigation_and_sitemap(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertDontSee('/cenovnik', false);

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertDontSee('/cenovnik', false);
    }
}
