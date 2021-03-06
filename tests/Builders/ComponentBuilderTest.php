<?php

namespace Spatie\IcalendarGenerator\Tests\Builders;

use Spatie\IcalendarGenerator\Builders\ComponentBuilder;
use Spatie\IcalendarGenerator\ComponentPayload;
use Spatie\IcalendarGenerator\Tests\TestCase;
use Spatie\IcalendarGenerator\Tests\TestClasses\DummyComponent;
use Spatie\IcalendarGenerator\Tests\TestClasses\DummyPropertyType;

class ComponentBuilderTest extends TestCase
{
    /** @test */
    public function it_can_build_a_component_payload_with_properties()
    {
        $payload = ComponentPayload::create('TEST');

        $payload->property(new DummyPropertyType('location', 'Antwerp'));

        $builder = new ComponentBuilder($payload);

        $this->assertEquals(
            <<<EOT
BEGIN:VTEST\r
location:Antwerp\r
END:VTEST
EOT
            ,
            $builder->build()
        );
    }

    /** @test */
    public function it_can_build_a_component_payload_with_property_alias()
    {
        $payload = ComponentPayload::create('TEST');

        $payload->property(new DummyPropertyType(['location', 'geo'], 'Antwerp'));

        $builder = new ComponentBuilder($payload);

        $this->assertEquals(
            <<<EOT
BEGIN:VTEST\r
location:Antwerp\r
geo:Antwerp\r
END:VTEST
EOT
            ,
            $builder->build()
        );
    }

    /** @test */
    public function it_can_build_a_component_payload_with_subcomponents()
    {
        $payload = ComponentPayload::create('TEST');

        $payload->subComponent(new DummyComponent('SUBCOMPONENT'));

        $builder = new ComponentBuilder($payload);

        $this->assertEquals(
            <<<EOT
BEGIN:VTEST\r
BEGIN:VDUMMY\r
name:SUBCOMPONENT\r
END:VDUMMY\r
END:VTEST
EOT
            ,
            $builder->build()
        );
    }

    /** @test */
    public function it_will_chip_a_line_when_it_becomes_too_long()
    {
        $payload = ComponentPayload::create('TEST');

        $payload->property(new DummyPropertyType('location', 'This is a really long text. Possibly you will never write a text like this in a property. But hey we support the RFC so let us chip it! You can maybe write some HTML in here that will make it longer than usual.'));

        $builder = new ComponentBuilder($payload);

        $this->assertEquals(
            <<<EOT
BEGIN:VTEST\r
location:This is a really long text. Possibly you will never write a text l\r
 ike this in a property. But hey we support the RFC so let us chip it! You \r
 can maybe write some HTML in here that will make it longer than usual.\r
END:VTEST
EOT
            ,
            $builder->build()
        );
    }
}
