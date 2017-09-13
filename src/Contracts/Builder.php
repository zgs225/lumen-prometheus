<?php

namespace Prometheus\Contracts;


use Illuminate\Contracts\Container\Container;

interface Builder
{
    /**
     * @param $namespace
     * @return Builder
     */
    public function _namespace($namespace);

    /**
     * @param $subsystem
     * @return Builder
     */
    public function subsystem($subsystem);

    /**
     * @param $name
     * @return Builder
     */
    public function name($name);

    /**
     * @param $help
     * @return Builder
     */
    public function help($help);

    /**
     * @param array $labels
     * @return Builder
     */
    public function labels(array $labels);

    /**
     * @return mixed
     */
    public function build();

    /**
     * @param Container $container
     * @return Builder
     */
    public function setContainer(Container $container);

    /**
     * @param Registry $registry
     * @return Builder
     */
    public function setRegistry(Registry $registry);
}