var r = require('jsrsasign');
var fs=require('fs');

function read(f) {
  return fs.readFileSync(f).toString();
}
function include(f) {
  eval.apply(global, [read(f)]);
}

include('../signature-settings.js');

var publickey = sigpublickey;

function doSign(val) {
  var prvkey = sigprvkey;
  var curve = "secp256k1"; //f1.curve1.value;
  var sigalg = "SHA256withECDSA";
  var msg1 = val;

  var sig = new r.Signature({"alg": sigalg, "prov": "cryptojs/jsrsa"});
  sig.initSign({'ecprvhex': prvkey, 'eccurvename': curve});
  sig.updateString(msg1);
  var sigValueHex = sig.sign();

  sign = 'SIGN:' + sigValueHex;
  pubk = '::PUBKEY:' + publickey;
  txtm = '::CONTENT:' + val;
  completed = sign + pubk + txtm;
  return completed;
}



process.argv.forEach(function (val, index, array) {
	if (index == 2) {
  		var b64string = val;
		var buf = new Buffer(b64string, 'base64'); // Ta-da
		console.log (doSign(buf.toString()));

	}
});