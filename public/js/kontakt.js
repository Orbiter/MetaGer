//based on https://github.com/encrypt-to/secure.contactform.php
/* The MIT License (MIT)
Copyright (c) 2013 Jan Wiegelmann

Permission is hereby granted, free of charge, to any person obtaining a copy of
this software and associated documentation files (the "Software"), to deal in
the Software without restriction, including without limitation the rights to
use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
the Software, and to permit persons to whom the Software is furnished to do so,
subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.*/
function encrypt() {
	if (window.crypto && window.crypto.getRandomValues) {
			var message = document.getElementById("message");
			console.log(message);
			if (message.value.indexOf("-----BEGIN PGP MESSAGE-----") !== -1 && message.value.indexOf("-----END PGP MESSAGE-----") !== -1) {
				// encryption done
			} else {
				var pub_key = openpgp.key.readArmored(document.getElementById("pubkey").innerHTML).keys[0];
				var plaintext = message.value;
				var ciphertext = openpgp.encryptMessage([pub_key],plaintext);
				message.value = ciphertext;
				alert(message.value);
				return true;
			}
	} else {
		window.alert("Fehler: Ihr Browser wird nicht unterst&uuml;tzt. Bitte installieren Sie einen aktuellen Broweser wie z.B. Mozilla Firefox.");
		return false;
	}
}

$(document).ready(function(){
	if(isEnglish()){
		$("button[type=submit]").html("encrypt and send");
	}else{
		$("button[type=submit]").html("Verschlüsseln und senden");
	}
	
	$(".contact").submit(function(){
		return encrypt(this);
	});
});

function isEnglish(){
	if(window.location.href.indexOf('/en/') == -1){
		return false;
	}else{
		return true;
	}
}