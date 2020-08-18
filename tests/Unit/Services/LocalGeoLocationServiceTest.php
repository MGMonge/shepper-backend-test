<?php

namespace Tests\Unit\Services;

use App\Services\Geolocation\InvalidCoordinatesException;
use App\Services\Geolocation\LocalGeolocationService;
use Tests\TestCase;

class LocalGeoLocationServiceTest extends TestCase
{
    /**
     * @var LocalGeolocationService
     */
    protected $SUT;

    public function _before(): void
    {
        $this->SUT = new LocalGeolocationService();
    }

    /** @test */
    function it_returns_true_when_given_coordinates_belongs_to_given_country()
    {
        $actual = $this->SUT->areCoordinatesInCountry('51.499479', '-0.085499', 'GB');

        $this->assertTrue($actual);
    }

    /** @test */
    function it_returns_false_when_given_coordinates_do_not_belong_to_given_country()
    {
        $actual = $this->SUT->areCoordinatesInCountry('51.499479', '-0.085499', 'AR');

        $this->assertFalse($actual);
    }

    /** @test */
    function it_throws_an_exception_when_place_is_not_found_by_given_coordinates_when_checking_country()
    {
        $this->expectException(InvalidCoordinatesException::class);

        $this->SUT->areCoordinatesInCountry('1', '-1', 'GB');
    }

    /** @test */
    function it_returns_a_label_by_given_coordinates()
    {
        $actual = $this->SUT->getLabelForCoordinates('51.499479', '-0.085499');

        $this->assertSame('London, GB', $actual);
    }

    /** @test */
    function it_throws_an_exception_when_place_is_not_found_by_given_coordinates_when_getting_the_label()
    {
        $this->expectException(InvalidCoordinatesException::class);

        $this->SUT->getLabelForCoordinates('1', '-1');
    }
}
