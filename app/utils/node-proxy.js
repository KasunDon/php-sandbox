var httpProxy = require('http-proxy'), fs = require('fs'), options = null;

var proxy = httpProxy.createProxy();

require('http').createServer(function(req, res) {
    var headers = req.headers;
    
    var c = fs.readFileSync('router.json', "utf-8");

    options = JSON.parse(c);

    proxy.web(req, res, {
        target: options[headers["x-forwarded-host"]],
        ws: true,
        xfwd: true
    });
}).listen(3000);


