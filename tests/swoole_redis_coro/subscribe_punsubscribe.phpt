--TEST--
swoole_redis_coro: redis subscribe and use punsubscribe
--SKIPIF--
<?php require __DIR__ . '/../include/skipif.inc'; ?>
--FILE--
<?php
require __DIR__ . '/../include/bootstrap.php';

go(function () {
    $redis = new Co\Redis();
    $ret = $redis->connect(REDIS_SERVER_HOST, REDIS_SERVER_PORT);
    assert($ret);

    $ret = $redis->subscribe(['channel1']);
    assert($ret);

    $ret = $redis->recv();
    assert($ret[0] == 'subscribe');
    assert($ret[1] == 'channel1');

    $ret = $redis->getDefer();
    assert(!$ret);

    $ret = $redis->set('a', '1');
    assert(!$ret);

    $ret = $redis->setDefer(false);
    assert(!$ret);

    $ret = $redis->punsubscribe(['channel1']);
    assert($ret);

    $ret = $redis->recv();
    assert($ret[0] == 'punsubscribe');
    assert($ret[1] == 'channel1');
    assert($ret[2] == 1);

    $ret = $redis->getDefer();
    assert(!$ret);

    $ret = $redis->set('a', '1');
    assert(!$ret);

    $ret = $redis->setDefer(false);
    assert(!$ret);

    $redis->close();
});

?>
--EXPECT--
Warning: Swoole\Coroutine\Redis::setDefer(): you should not use setDefer after subscribe in /swoole-src/tests/swoole_redis_coro/subscribe_punsubscribe.php on line 22

Warning: Swoole\Coroutine\Redis::setDefer(): you should not use setDefer after subscribe in /swoole-src/tests/swoole_redis_coro/subscribe_punsubscribe.php on line 39
