Drupal TCP Server
=================

Drupal TCP Server is a PHP based simple TCP socket server running on port 9000. The server is very light weight and interestingly Bootstrap ontime only. So this means no bootstrap for any incoming request.

After running the service, any one can call any Drupal function in json in bellow format:

{"function":"node_load","argument":[1]}

### Performance: for 315 request requested node_load(1)
##### Through HTTP Server
[bappa@bus000356 node_benchmark]$ ab -n 315 http://d6.myserver.com:80/test.php

Concurrency Level:      1

Time taken for tests:   32.982 seconds

Complete requests:      315

##### Through Drupal TCP server:
[bappa@bus000356 node_benchmark]$ node test_drupal-tcp_server.js

^C

connect average: 0.1746031746031746 ms

data average: 1.8761904761904762 ms

Total connection : 315

Total elapsed time : 1.775 sec

### Usage
Copy the **drupald** file inside /etc/init.d and use the command bellow
$ service drupald start
$ service drupald stop
$ service drupald restart
