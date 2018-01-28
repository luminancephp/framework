# Test Results

We have compared Luminance against other frameworks for speed comparison. Here is the hardware tested on:

MacBook Pro 2015, 8 GB RAM, 128 GB SSD, running OS X High Sierra

## Luminance vs Slim

These tests will access the default home page of each framework. These are default, non-modified framework configuration.

### Slim

Command:

Output:

```

Benchmarking slim.local (be patient)
Completed 100 requests
Completed 200 requests
Completed 300 requests
Completed 400 requests
Completed 500 requests
Finished 500 requests


Server Software:        nginx/1.12.2
Server Hostname:        slim.local
Server Port:            80

Document Path:          /
Document Length:        987 bytes

Concurrency Level:      50
Time taken for tests:   2.520 seconds
Complete requests:      500
Failed requests:        0
Total transferred:      670500 bytes
HTML transferred:       493500 bytes
Requests per second:    198.39 [#/sec] (mean)
Time per request:       252.029 [ms] (mean)
Time per request:       5.041 [ms] (mean, across all concurrent requests)
Transfer rate:          259.81 [Kbytes/sec] received

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    0   0.4      0       2
Processing:    27  241  38.7    241     315
Waiting:       27  241  38.7    241     315
Total:         29  241  38.3    241     316

Percentage of the requests served within a certain time (ms)
  50%    241
  66%    248
  75%    257
  80%    264
  90%    279
  95%    284
  98%    293
  99%    301
 100%    316 (longest request)
```

Average: 5.041ms per request

### Luminance

```
Benchmarking luminance.local (be patient)
Completed 100 requests
Completed 200 requests
Completed 300 requests
Completed 400 requests
Completed 500 requests
Finished 500 requests


Server Software:        nginx/1.12.2
Server Hostname:        luminance.local
Server Port:            80

Document Path:          /
Document Length:        13 bytes

Concurrency Level:      50
Time taken for tests:   0.754 seconds
Complete requests:      500
Failed requests:        0
Total transferred:      183500 bytes
HTML transferred:       6500 bytes
Requests per second:    663.39 [#/sec] (mean)
Time per request:       75.370 [ms] (mean)
Time per request:       1.507 [ms] (mean, across all concurrent requests)
Transfer rate:          237.76 [Kbytes/sec] received

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    0   0.4      0       2
Processing:     8   72  10.9     73      98
Waiting:        8   72  10.9     73      98
Total:         10   72  10.6     73      99

Percentage of the requests served within a certain time (ms)
  50%     73
  66%     76
  75%     78
  80%     79
  90%     81
  95%     84
  98%     90
  99%     93
 100%     99 (longest request)
 ```
 
 Average: 1.507ms