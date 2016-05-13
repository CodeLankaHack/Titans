
$('#save').click(function () {
    $('#modal-description').text('Waiting for NFC tag...');
    $('#modalBtn').trigger('click');
    NFC();
});

function NFC() {
     //Handle the Cordova pause and resume events
                nfc.enabled(
                        function () {
                        },
                        // msg is one of NO_NFC (no hardware support) or NFC_DISABLED (supported but disabled)
                                function (msg) {
                                }
                        );

                        function nfcHandler(nfcEvent) {
                            var firstname = $('#firstname').val();
                            var lastname = $('#lastname').val();
                            var postition = $('#postition').val();
                            var email = $('#email').val();
                            var telephone = $('#telephone').val();
                            var mobile = $('#mobile').val();
                            var city = $('#city').val();
                            var website = $('#website').val();

                            var records = [
                                ndef.textRecord("First Name: " + firstname+"\n"+"Last Name: "+lastname+"\n"+"Position: "+postition+"\n"+"Email: "+email+"\n"+"Telephone: "+telephone+"\n"+"Mobile: "+mobile+"\n"+"City: "+city+"\n"+"Website: "+website+"\n"),
                                ndef.uriRecord("http://plugins.telerik.com/plugin/nfc"),
                                ndef.mimeMediaRecord("text/blah", nfc.stringToBytes("Blah!"))
                            ];

                            nfc.write(
                                    records,
                                    function () {
                                        $('#modal-description').text('Successfully Saved to NFC');
                                    },
                                    function () {
                                        $('#modal-description').text('Successfully Saved to NFC');
                                    }
                            );
                        }
                        ;

                        nfc.addNdefListener(
                                nfcHandler,
                                function () {
                                    console.log("Success, listener added. You can now scan a tag.");
                                },
                                function (error) {
                                    alert("Adding the listener failed.");
                                }
                        );
}
