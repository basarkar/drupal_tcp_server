var net = require('net');
var stats = require('./statistics');

var totalTime = Date.now();

function connect() {
  //process.stdout.write('#');
  var time = Date.now();
  var conn = net.createConnection(9000);

  conn.on('connect', function(){
    stats.collect('connect', Date.now() - time);
    //console.log('connected');
    conn.write('{"function":"node_load","argument":[1]}' + "\n");
    conn.on('data', function(){
      stats.collect('data', Date.now() - time);
    });
    conn.end();
  });

  conn.on('close', function(){
    //console.log('closed');
  })
}

setInterval(connect, 5);

process.on('SIGINT', function(){
  totalTime = ((Date.now() - totalTime)/1000);
  stats.summarize();
  console.log('Total elapsed time : ' + totalTime + ' sec');
  process.exit();
});
