Lumen-Prometheus
===

Prometheus client for Lumen 5.4. Now it's persistence stored supported by redis.

### Usage

#### Counter

``` php
$gauge = \Prometheus\Facades\Prometheus::counter()
    ->_namespace('lord_v3')
    ->subsystem('test')
    ->name('count')
    ->help('测试计数器')
    ->build();
```

#### Gauge

``` php
$gauge = \Prometheus\Facades\Prometheus::gauge()
    ->_namespace('lord_v3')
    ->subsystem('test')
    ->name('gauge')
    ->help('测试仪表盘')
    ->build();
```

### TODO

+ [x] Counter
+ [x] Gauge
+ [ ] Summary
+ [ ] Histogram
+ [ ] Untyped
