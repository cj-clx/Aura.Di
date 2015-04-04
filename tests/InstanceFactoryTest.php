<?php
namespace Aura\Di;

class InstanceFactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $resolver;

    protected $config;

    protected function setUp()
    {
        parent::setUp();
        $this->resolver = new Resolver;
    }

    protected function newInstanceFactory(
        $class,
        array $params = array(),
        array $setter = array()
    ) {
        return new InstanceFactory($this->resolver, $class, $params, $setter);
    }

    public function test__invoke()
    {
        $other = $this->resolver->newInstance('Aura\Di\FakeOtherClass');

        $factory = $this->newInstanceFactory(
            'Aura\Di\FakeChildClass',
            array(
                'foo' => 'foofoo',
                'zim' => $other,
            ),
            array(
                'setFake' => 'fakefake',
            )
        );

        $actual = $factory();

        $this->assertInstanceOf('Aura\Di\FakeChildClass', $actual);
        $this->assertInstanceOf('Aura\Di\FakeOtherClass', $actual->getZim());
        $this->assertSame('foofoo', $actual->getFoo());
        $this->assertSame('fakefake', $actual->getFake());


        // create another one, should not be the same
        $extra = $factory();
        $this->assertNotSame($actual, $extra);
    }
}