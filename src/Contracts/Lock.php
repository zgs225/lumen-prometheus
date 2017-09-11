<?php

namespace Prometheus\Contracts;

interface Lock
{
    public function lock();

    public function unlock();
}